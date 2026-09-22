import sys
import os
import base64
from zeep import Client
from zeep.transports import Transport
import requests
import json
import re
from lxml import etree

# 1. Definición de la URL del Web Service de Recepción (Producción o Pruebas)
# Producción: https://cel.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl
# Pruebas:    https://celcer.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl

# WSDL_RECEPCION = "https://cel.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl"
# WSDL_RECEPCION = "https://celcer.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl"
# WSDL_AUTORIZACION = "https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl"

# FOLDER_NO_AUTORIZADO = "C:\\xampp\\htdocs\\deep_script\\php\\comprobantes\\entidades\\entidad_6\\CE6\\FACTURAS\\No_autorizados"
# FOLDER_AUTORIZADO = "C:\\xampp\\htdocs\\deep_script\\php\\comprobantes\\entidades\\entidad_6\\CE6\\FACTURAS\\Autorizados"

################ funcion para cuardar el xml en carpeta ###################################

def guardar_xml_en_carpeta(contenido_xml, carpeta_destino, nombre_archivo):
    try:
        # 1. Crear la carpeta si aún no existe
        os.makedirs(carpeta_destino, exist_ok=True)

        # 2. Construir la ruta completa del archivo
        ruta_completa = os.path.join(carpeta_destino, nombre_archivo)

        # 3. Guardar la variable XML en el archivo (usando encoding UTF-8)
        with open(ruta_completa, "w", encoding="utf-8") as archivo:
            archivo.write(contenido_xml)
        # print(f"XML guardado exitosamente en: {ruta_completa}")
        return True

    except Exception as e:
        print(f"Error al guardar el XML: {e}")
        return False


################ fin funcion para cuardar el xml en carpeta ################################



################ funcion para descargar el xml #############################################

# def descargar_xml_autorizado(clave_acceso, carpeta_destino,WSDL_AUTORIZACION):
#     try:
#         # 1. Conectar con el servicio del SRI
#         client = Client(WSDL_AUTORIZACION)
        
#         # 2. Consultar por la clave de acceso
#         respuesta = client.service.autorizacionComprobante(claveAccesoComprobante=clave_acceso)
        
#         # Validar si el SRI devolvió autorizaciones
#         autorizaciones = respuesta.autorizaciones
#         if not autorizaciones or not hasattr(autorizaciones, 'autorizacion') or len(autorizaciones.autorizacion) == 0:
#             return {
#                 "exito": False,
#                 "mensaje": "No se encontraron autorizaciones para la clave de acceso proporcionada."
#             }

#         autorizacion = autorizaciones.autorizacion[0]
#         estado = getattr(autorizacion, 'estado', 'DESCONOCIDO')

#         if estado == "AUTORIZADO":
#             # 3. Extraer el contenido del XML autorizado (viene en la propiedad 'comprobante')
#             xml_contenido = autorizacion.comprobante

#             # 4. Crear la carpeta si no existe
#             os.makedirs(carpeta_destino, exist_ok=True)

#             # 5. Definir la ruta del archivo y guardarlo
#             ruta_xml = os.path.join(carpeta_destino, f"{clave_acceso}.xml")
#             with open(ruta_xml, "w", encoding="utf-8") as file:
#                 file.write(xml_contenido)

#             return {
#                 "exito": True,
#                 "estado": estado,
#                 "numeroAutorizacion": getattr(autorizacion, 'numeroAutorizacion', 'N/A'),
#                 "fechaAutorizacion": str(getattr(autorizacion, 'fechaAutorizacion', '')),
#                 "ruta_guardado": ruta_xml,
#                 "msj": "XML Autorizado descargado y guardado con éxito."
#             }
#         else:
#             return {
#                 "exito": False,
#                 "estado": estado,
#                 "mensaje": f"El comprobante no está AUTORIZADO. Estado actual: {estado}"
#             }

#     except Exception as e:
#         return {
#             "exito": False,
#             "mensaje": f"Error al consultar el SRI: {str(e)}"
#         }

################ fin funcion para descargar el xml #############################################


################ funcion para enviar el xml firmado  por ruta de archivo ###################

def enviar_comprobante_firmado(ruta_xml_firmado,ruta_xml_enviado,ruta_xml_rechazados,WSDL_RECEPCION):
    """
    Lee un archivo XML firmado, lo convierte a bytes Base64 
    y lo envía al Servicio Web de Recepción del SRI.
    """
    if not os.path.exists(ruta_xml_firmado):
        print(f"Error: No se encuentra el archivo en {ruta_xml_firmado}")
        return None

    try:
        # 2. Leer el archivo XML firmado como bytes
        result = {}
        with open(ruta_xml_firmado, "rb") as xml_file:
            xml_bytes = xml_file.read()
        with open(ruta_xml_firmado, "r", encoding="utf-8") as xml_file:
            xml_texto = xml_file.read()

        # 3. Configurar el cliente SOAP con timeout para evitar bloqueos
        session = requests.Session()
        session.timeout = 15  # Timeout de 15 segundos
        client = Client(wsdl=WSDL_RECEPCION, transport=Transport(session=session))

        # 4. Invocar el método 'validarComprobante' pasando los bytes del XML
        # El SRI requiere que el parámetro sea un arreglo de bytes (byte[])
        respuesta = client.service.validarComprobante(xml_bytes)
        print(respuesta)

        # 5. Procesar la respuesta del SRI
        estado = respuesta.estado
        # print(f"\n--- RESPUESTA DE RECEPCIÓN SRI ---")
        # print(f"Estado de la Recepción: {estado}")

        result[1] = clave_acceso;
        result[2] = estado;
        if estado == "RECIBIDA":            
            guardar_xml_en_carpeta(xml_texto, ruta_xml_enviado, clave_acceso+".xml")
            result[0]= 1            
            result[3] = "El comprobante fue recibido correctamente y está pendiente de autorización.";
            # print("El comprobante fue recibido correctamente y está pendiente de autorización.")
        
        elif estado == "DEVUELTA":

            result[0] = -1
            # print("El comprobante fue DEVUELTO por el SRI con las siguientes observaciones:")
            # Recorrer los errores/observaciones devueltos

            if hasattr(respuesta, 'comprobantes') and respuesta.comprobantes:
                for comp in respuesta.comprobantes.comprobante:
                    if hasattr(comp, 'mensajes') and comp.mensajes:
                        detalle_mensajes = "";
                        detalle_xml_mensaje = "";
                        for msg in comp.mensajes.mensaje:
                            identificador = getattr(msg, 'identificador', 'N/A')
                            mensaje = getattr(msg, 'mensaje', 'Sin mensaje')
                            tipo = getattr(msg, 'tipo', 'N/A')
                            info_adicional = getattr(msg, 'informacionAdicional', 'N/A')


                            detalle_xml_mensaje = f""" <estado>{estado}</estado>
    <comprobantes>
        <comprobante>
            <claveAcceso>0608202601175783157100120010020000061571234567815</claveAcceso>
            <mensajes>
                <mensaje>
                    <identificador>{identificador}</identificador>
                    <mensaje>{mensaje}</mensaje>
                    <informacionAdicional>{info_adicional}</informacionAdicional>
                    <tipo>{tipo}</tipo>
                </mensaje>
            </mensajes>
        </comprobante>
    </comprobantes>"""

                    root = etree.fromstring(xml_bytes)
                    fragmento_doc = etree.fromstring(f"<root>{detalle_xml_mensaje}</root>".encode('utf-8'))
                    for elemento in fragmento_doc:
                        root.append(elemento)
                    xml_final_texto = etree.tostring(root, encoding="utf-8", pretty_print=True).decode("utf-8")

                    result[3] = "El comprobante fue DEVUELTO por el SRI con las siguientes observaciones:"+detalle_mensajes                
                    guardar_xml_en_carpeta(xml_final_texto, ruta_xml_rechazados, clave_acceso+".xml")

        return result

    except Exception as e:

        result = []        
        result[0] = -1
        result[1] = clave_acceso;
        result[2] = estado;        
        result[3] = f"Error al comunicar con el servicio de Recepción del SRI: {e}"  

        # print(f"Error al comunicar con el servicio de Recepción del SRI: {e}")
        return result
        # return None


################ fin funcion para enviar el xml firmado  por ruta de archivo ################


################ funcion para verificar el xml firmado  por clave de acceso ###################

def verificar_autorizacion(clave_acceso,WSDL_AUTORIZACION,ruta_xml_autorizado,ruta_xml_no_autorizado):
    # 1. Validación simple de la clave de acceso (debe tener 49 dígitos)
    if not clave_acceso or len(clave_acceso) != 49:
        return {"estado": "ERROR", "mensaje": "La clave de acceso debe tener exactamente 49 dígitos."}

    try:
        # 2. Conexión al servicio de Autorización
        cliente = Client(WSDL_AUTORIZACION)
        respuesta = cliente.service.autorizacionComprobante(clave_acceso)
        # print(respuesta)

        # 3. Validar si existen autorizaciones registradas
        autorizaciones = respuesta.autorizaciones
        if not autorizaciones or not autorizaciones.autorizacion:
            return {"estado": "SIN_REGISTRO", "mensaje": "No se encontraron registros para esta clave de acceso."}

        # Obtener la última respuesta del SRI
        aut = autorizaciones.autorizacion[0]
        estado = aut.estado
        contenido_xml = aut.comprobante

        # 4. Retornar según el estado obtenido
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
            guardar_xml_en_carpeta(xml_auto, ruta_xml_autorizado, clave_acceso+".xml")
            return {
                "0":1,
                "1": aut.numeroAutorizacion,
                "2": "AUTORIZADO",
                "3": "El comprobante fue autorizado",
                "4": str(aut.fechaAutorizacion),
                "5": aut.ambiente
            }
        
        # En caso de NO AUTORIZADO o DEVUELTO
        # print(estado)
        # print(aut)
        errores = []
        if hasattr(aut, 'mensajes') and aut.mensajes:
            # print('entra')
            for m in aut.mensajes.mensaje:
                detalle = getattr(m, 'informacionAdicional', m.mensaje)
                errores.append(f"{m.mensaje} ({detalle})")

        guardar_xml_en_carpeta(contenido_xml, ruta_xml_no_autorizado, clave_acceso+".xml")
        return {
            "0":-1,
            "1": clave_acceso,
            "2": estado,
            "3": " - ".join(errores) if errores else "Sin detalle de mensajes.",
            "4": str(aut.fechaAutorizacion),
            "5": aut.ambiente
        }

    except Exception as e:
        # return {"estado": "ERROR", "mensaje": f"Error de conexión con el SRI: {str(e)}"}
        return {
                "0":-1,
                "1": clave_acceso,
                "2": "ERROR",
                "3": f"Error de conexión con el SRI: {str(e)}",
                "4": str(aut.fechaAutorizacion),
                "5": aut.ambiente
            }


################ funcion para verificar el xml firmado  por clave de acceso ###################


# --- EJEMPLO DE EJECUCIÓN ---
if __name__ == "__main__":

    tipo = sys.argv[1].strip()
    clave_acceso = sys.argv[2].strip()
    
    # print(tipo)
    result = {}
    if tipo=="1":
        ######## enviar comprobante #########
        ruta_xml_firmado = sys.argv[3].strip()
        ruta_xml_enviado = sys.argv[4].strip()
        ruta_xml_rechazados = sys.argv[5].strip()

        archivo_xml = ruta_xml_firmado+clave_acceso+".xml"
        WSDL_RECEPCION = sys.argv[6].strip()
        result = enviar_comprobante_firmado(archivo_xml,ruta_xml_enviado,ruta_xml_rechazados,WSDL_RECEPCION)
        # print(result)
    elif tipo=="2":

        ######## validar comprobante #########
        ruta_xml_autorizado = sys.argv[3].strip()
        ruta_xml_no_autorizado = sys.argv[4].strip()
        WSDL_AUTORIZACION = sys.argv[5].strip()
        result = verificar_autorizacion(clave_acceso,WSDL_AUTORIZACION,ruta_xml_autorizado,ruta_xml_no_autorizado)

    elif tipo=="3":
        ruta_xml_autorizado = sys.argv[3].strip()        
        WSDL_AUTORIZACION = sys.argv[4].strip()
        result = descargar_xml_autorizado("2209202601070216417900110010030000020261234567813", ruta_xml_autorizado,WSDL_AUTORIZACION)


    print(json.dumps(result, ensure_ascii=False))

    # print(clave_acceso);

    # Ruta a tu comprobante XML (Factura, Retención, Nota de Crédito, etc.) firmado
    # ruta_base = "C:\\xampp\\htdocs\\deep_script\\php\\comprobantes\\entidades\\entidad_6\\CE6\\FACTURAS\\Firmados\\"
    # clave_acceso = "2209202601070216417900110010030000020141234567818"
    # archivo_xml = ruta_base+clave_acceso+".xml"
    # enviar_comprobante_firmado(archivo_xml)
    # verificado = verificar_autorizacion(clave_acceso)
    # print(verificado)