import sys
import os
import json
import tempfile
import getpass  # <-- NUEVO: Para obtener el usuario actual
from zeep import Client
from zeep.cache import SqliteCache
from zeep.transports import Transport
from lxml import etree
import requests
from requests.exceptions import RequestException

SRI_WSDL_TIMEOUT = 15
SRI_OPERATION_TIMEOUT = 20
SRI_WSDL_CACHE_TTL = 86400


def crear_cliente_sri(wsdl):
    session = requests.Session()
    # CORRECCIÓN: Agregar el nombre del usuario al archivo de caché para evitar conflictos de permisos
    usuario_actual = getpass.getuser()
    cache_path = os.path.join(tempfile.gettempdir(), f"diskcoversystem_zeep_cache_{usuario_actual}.db")
    
    transport = Transport(
        session=session,
        cache=SqliteCache(path=cache_path, timeout=SRI_WSDL_CACHE_TTL),
        timeout=SRI_WSDL_TIMEOUT,
        operation_timeout=SRI_OPERATION_TIMEOUT,
    )
    return Client(wsdl=wsdl, transport=transport)


def verificar_enlace(url):
    try:
        # Los WSDL del SRI no aceptan HEAD; GET valida el mismo acceso que usa Zeep.
        with requests.get(url, timeout=5, allow_redirects=True, stream=True) as response:
            if 200 <= response.status_code < 300:
                return True, response.status_code
            return False, response.status_code
    except RequestException as e:
        # Captura errores de conexión, DNS, timeouts, etc.
        return False, str(e)


def guardar_xml_en_carpeta(contenido_xml, carpeta_destino, nombre_archivo):
    try:
        os.makedirs(carpeta_destino, exist_ok=True)
        ruta_completa = os.path.join(carpeta_destino, nombre_archivo)
        with open(ruta_completa, "w", encoding="utf-8") as archivo:
            archivo.write(contenido_xml)
        return True
    except Exception as e:
        print(f"Error al guardar el XML: {e}", file=sys.stderr)
        return False


def enviar_comprobante_firmado(ruta_xml_firmado, ruta_xml_enviado, ruta_xml_rechazados, WSDL_RECEPCION):
    if not os.path.exists(ruta_xml_firmado):
        print(f"Error: No se encuentra el archivo en {ruta_xml_firmado}")
        return None

    clave_acceso = os.path.splitext(os.path.basename(ruta_xml_firmado))[0]
    estado = "ERROR"
    result = {}
    
    try:
        with open(ruta_xml_firmado, "rb") as xml_file:
            xml_bytes = xml_file.read()
        with open(ruta_xml_firmado, "r", encoding="utf-8") as xml_file:
            xml_texto = xml_file.read()

        activo, resultado = verificar_enlace(WSDL_RECEPCION)

        if not activo:
            result[0] = 99            
            result[1] = clave_acceso
            result[2] = "fuera de linea"
            result[3] = "Servidor fuera de linea, intente mas tarde autorizar el documento, aun asi se procedera a grabar el comprobante"
            result[4] = f"{resultado}"
            return result

        client = crear_cliente_sri(WSDL_RECEPCION)
        respuesta = client.service.validarComprobante(xml_bytes)

        estado = respuesta.estado
        result[1] = clave_acceso
        result[2] = estado
        
        if estado == "RECIBIDA":            
            guardar_xml_en_carpeta(xml_texto, ruta_xml_enviado, clave_acceso+".xml")
            result[0] = 1            
            result[3] = "El comprobante fue recibido correctamente y está pendiente de autorización."
            result[4] = ""
        
        elif estado == "DEVUELTA":
            result[0] = -1
            if hasattr(respuesta, 'comprobantes') and respuesta.comprobantes:
                mensajes_acumulados = []
                xml_mensajes_nodos = ""
                
                # CORRECCIÓN: Acumular todos los mensajes en lugar de sobrescribirlos
                for comp in respuesta.comprobantes.comprobante:
                    if hasattr(comp, 'mensajes') and comp.mensajes:
                        for msg in comp.mensajes.mensaje:
                            identificador = getattr(msg, 'identificador', 'N/A') or "N/A"
                            mensaje = getattr(msg, 'mensaje', 'Sin mensaje') or "Sin mensaje"
                            tipo = getattr(msg, 'tipo', 'N/A') or "N/A"
                            info_adicional = getattr(msg, 'informacionAdicional', 'N/A') or "N/A"
                            
                            if identificador in ('58', '70', '43'):
                                result[0] = 0

                            result[4] = identificador # Mantenemos el último identificador por compatibilidad
                            mensajes_acumulados.append(f"{mensaje} {info_adicional}")
                            
                            xml_mensajes_nodos += f"""
                    <mensaje>
                        <identificador>{identificador}</identificador>
                        <mensaje>{mensaje}</mensaje>
                        <informacionAdicional>{info_adicional}</informacionAdicional>
                        <tipo>{tipo}</tipo>
                    </mensaje>"""
                
                if mensajes_acumulados:
                    root = etree.fromstring(xml_bytes)
                    detalle_xml_mensaje = f"""<estado>{estado}</estado>
    <comprobantes>
        <comprobante>
            <claveAcceso>{clave_acceso}</claveAcceso>
            <mensajes>{xml_mensajes_nodos}
            </mensajes>
        </comprobante>
    </comprobantes>"""
                    
                    fragmento_doc = etree.fromstring(f"<root>{detalle_xml_mensaje}</root>".encode('utf-8'))
                    for elemento in fragmento_doc:
                        root.append(elemento)
                        
                    xml_final_texto = etree.tostring(root, encoding="utf-8", pretty_print=True).decode("utf-8")
                    result[3] = "El comprobante fue DEVUELTO por el SRI. Observaciones: " + " | ".join(mensajes_acumulados)
                    guardar_xml_en_carpeta(xml_final_texto, ruta_xml_rechazados, clave_acceso+".xml")

        return result

    except Exception as e:
        result[0] = -1
        result[1] = clave_acceso
        result[2] = estado   
        result[3] = f"Error al comunicar con el servicio de Recepcion del SRI: {str(e)}"
        result[4] = ""
        return result


def verificar_autorizacion(clave_acceso, WSDL_AUTORIZACION, ruta_xml_autorizado, ruta_xml_no_autorizado):
    # 1. Validación simple de la clave de acceso
    if not clave_acceso or len(clave_acceso) != 49:
        return {"0": -1, "1": clave_acceso, "2": "ERROR", "3": "La clave de acceso debe tener exactamente 49 dígitos.", "4": "", "5": ""}

    aut = None
    # CORRECCIÓN: Inicializar el diccionario result para evitar NameError si el enlace falla
    result = {}
    
    try:
        activo, resultado = verificar_enlace(WSDL_AUTORIZACION)
        
        if not activo:
            result[0] = 99            
            result[1] = clave_acceso
            result[2] = "fuera de linea"
            result[3] = "Servidor fuera de linea, intente mas tarde autorizar el documento, aun asi se procedera a grabar el comprobante"
            result[4] = f"{resultado}"
            result[5] = ""
            return result

        cliente = crear_cliente_sri(WSDL_AUTORIZACION)
        respuesta = cliente.service.autorizacionComprobante(clave_acceso)

        autorizaciones = respuesta.autorizaciones
        
        if not autorizaciones or not autorizaciones.autorizacion:
            return {"0": -1,
                    "1": clave_acceso,
                    "2": "SIN_REGISTRO",
                    "3": "No se encontraron registros para esta clave de acceso.",
                    "4": "",
                    "5": "",
                }

        aut = autorizaciones.autorizacion[0]
        estado = aut.estado
        contenido_xml = aut.comprobante

        if estado == "AUTORIZADO":
            contenido_xml_sin = "\n".join([linea.strip() for linea in contenido_xml.splitlines() if linea.strip()])
            xml_auto = f"""<?xml version="1.0" encoding="UTF-8"?>
<autorizacion>
    <estado>AUTORIZADO</estado>
    <numeroAutorizacion>{aut.numeroAutorizacion}</numeroAutorizacion>
    <fechaAutorizacion>{str(aut.fechaAutorizacion)}</fechaAutorizacion>
    <comprobante><![CDATA[{contenido_xml_sin}]]></comprobante>
    <mensajes/>
</autorizacion>"""
            if not guardar_xml_en_carpeta(xml_auto, ruta_xml_autorizado, clave_acceso+".xml"):
                return {
                    "0": -5, "1": clave_acceso, "2": "ERROR",
                    "3": "El comprobante fue autorizado por el SRI, pero no se pudo guardar el XML autorizado.",
                    "4": str(aut.fechaAutorizacion), "5": aut.ambiente
                }
            return {
                "0": 1, "1": aut.numeroAutorizacion, "2": "AUTORIZADO",
                "3": "El comprobante fue autorizado", "4": str(aut.fechaAutorizacion), "5": aut.ambiente
            }
        
        errores = []
        mensajes = []
        if hasattr(aut, 'mensajes') and aut.mensajes:
            mensajes = aut.mensajes.mensaje
            if not isinstance(mensajes, list):
                mensajes = [mensajes]
            for mensaje in mensajes:
                detalle = getattr(mensaje, 'informacionAdicional', '') or mensaje.mensaje
                errores.append(f"{mensaje.mensaje} ({detalle})")

        contenido_xml_sin = "\n".join([linea.strip() for linea in contenido_xml.splitlines() if linea.strip()])
        raiz = etree.Element("autorizacion")
        etree.SubElement(raiz, "estado").text = estado
        etree.SubElement(raiz, "fechaAutorizacion").text = str(aut.fechaAutorizacion)
        etree.SubElement(raiz, "comprobante").text = etree.CDATA(contenido_xml_sin)
        mensajes_xml = etree.SubElement(raiz, "mensajes")
        for mensaje in mensajes:
            mensaje_xml = etree.SubElement(mensajes_xml, "mensaje")
            for campo in ("identificador", "mensaje", "informacionAdicional", "tipo"):
                valor = getattr(mensaje, campo, "")
                etree.SubElement(mensaje_xml, campo).text = str(valor or "")
                
        xml_auto = etree.tostring(raiz, encoding="utf-8", xml_declaration=True, pretty_print=True).decode("utf-8")
        guardar_xml_en_carpeta(xml_auto, ruta_xml_no_autorizado, clave_acceso+".xml")
        
        return {
            "0": -1, "1": clave_acceso, "2": estado,
            "3": " - ".join(errores) if errores else "Sin detalle de mensajes.",
            "4": str(aut.fechaAutorizacion), "5": aut.ambiente
        }

    except Exception as e:
        if aut is not None:
            mensajes = []
            try:
                mensajes_respuesta = getattr(aut, "mensajes", None)
                if mensajes_respuesta:
                    mensajes = mensajes_respuesta.mensaje
                    if not isinstance(mensajes, list):
                        mensajes = [mensajes]
            except Exception:
                mensajes = []

            raiz = etree.Element("autorizacion")
            etree.SubElement(raiz, "estado").text = getattr(aut, "estado", "ERROR")
            etree.SubElement(raiz, "fechaAutorizacion").text = str(getattr(aut, "fechaAutorizacion", ""))
            comprobante = getattr(aut, "comprobante", "")
            contenido_xml_sin = "\n".join(linea.strip() for linea in comprobante.splitlines() if linea.strip())
            etree.SubElement(raiz, "comprobante").text = etree.CDATA(contenido_xml_sin)
            mensajes_xml = etree.SubElement(raiz, "mensajes")
            for mensaje in mensajes:
                mensaje_xml = etree.SubElement(mensajes_xml, "mensaje")
                for campo in ("identificador", "mensaje", "informacionAdicional", "tipo"):
                    valor = getattr(mensaje, campo, "")
                    etree.SubElement(mensaje_xml, campo).text = str(valor or "")
            xml_auto = etree.tostring(raiz, encoding="utf-8", xml_declaration=True, pretty_print=True).decode("utf-8")
            guardar_xml_en_carpeta(xml_auto, ruta_xml_no_autorizado, clave_acceso + ".xml")

        return {
            "0": -5, "1": clave_acceso, "2": "ERROR",
            "3": f"Error de conexión con el SRI: {str(e)}", "4": "", "5": "",
        }


# CORRECCIÓN: Función descomentada y adaptada para usar crear_cliente_sri
def descargar_xml_autorizado(clave_acceso, carpeta_destino, WSDL_AUTORIZACION):
    try:
        client = crear_cliente_sri(WSDL_AUTORIZACION)
        respuesta = client.service.autorizacionComprobante(claveAccesoComprobante=clave_acceso)
        
        autorizaciones = respuesta.autorizaciones
        if not autorizaciones or not hasattr(autorizaciones, 'autorizacion') or len(autorizaciones.autorizacion) == 0:
            return {"exito": False, "mensaje": "No se encontraron autorizaciones para la clave de acceso proporcionada."}

        autorizacion = autorizaciones.autorizacion[0]
        estado = getattr(autorizacion, 'estado', 'DESCONOCIDO')

        if estado == "AUTORIZADO":
            xml_contenido = autorizacion.comprobante
            os.makedirs(carpeta_destino, exist_ok=True)
            ruta_xml = os.path.join(carpeta_destino, f"{clave_acceso}.xml")
            with open(ruta_xml, "w", encoding="utf-8") as file:
                file.write(xml_contenido)

            return {
                "exito": True,
                "estado": estado,
                "numeroAutorizacion": getattr(autorizacion, 'numeroAutorizacion', 'N/A'),
                "fechaAutorizacion": str(getattr(autorizacion, 'fechaAutorizacion', '')),
                "ruta_guardado": ruta_xml,
                "msj": "XML Autorizado descargado y guardado con éxito."
            }
        else:
            return {"exito": False, "estado": estado, "mensaje": f"El comprobante no está AUTORIZADO. Estado actual: {estado}"}

    except Exception as e:
        return {"exito": False, "mensaje": f"Error al consultar el SRI: {str(e)}"}


# --- EJEMPLO DE EJECUCIÓN ---
if __name__ == "__main__":
    if len(sys.argv) < 3:
        print(json.dumps({"error": "Faltan argumentos. Uso: python script.py <tipo> <clave_acceso> ..."}))
        sys.exit(1)

    tipo = sys.argv[1].strip()
    clave_acceso = sys.argv[2].strip()
    
    result = {}
    if tipo == "1":
        ruta_xml_firmado = sys.argv[3].strip()
        ruta_xml_enviado = sys.argv[4].strip()
        ruta_xml_rechazados = sys.argv[5].strip()
        WSDL_RECEPCION = sys.argv[6].strip()
        
        archivo_xml = os.path.join(ruta_xml_firmado, clave_acceso + ".xml")
        result = enviar_comprobante_firmado(archivo_xml, ruta_xml_enviado, ruta_xml_rechazados, WSDL_RECEPCION)
        
    elif tipo == "2":
        ruta_xml_autorizado = sys.argv[3].strip()
        ruta_xml_no_autorizado = sys.argv[4].strip()
        WSDL_AUTORIZACION = sys.argv[5].strip()
        result = verificar_autorizacion(clave_acceso, WSDL_AUTORIZACION, ruta_xml_autorizado, ruta_xml_no_autorizado)

    elif tipo == "3":
        ruta_xml_autorizado = sys.argv[3].strip()        
        WSDL_AUTORIZACION = sys.argv[4].strip()
        result = descargar_xml_autorizado(clave_acceso, ruta_xml_autorizado, WSDL_AUTORIZACION)

    print(json.dumps(result, ensure_ascii=False))