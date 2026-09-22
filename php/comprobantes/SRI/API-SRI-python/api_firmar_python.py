import os
import base64
from lxml import etree
from cryptography.hazmat.primitives.serialization import pkcs12
from cryptography.hazmat.primitives import hashes, serialization
from cryptography.hazmat.primitives.asymmetric import padding
from cryptography.hazmat.backends import default_backend

def firmar_xml_sri(ruta_xml_sin_firmar, ruta_p12, contrasena_p12, ruta_xml_salida):
    if not os.path.isfile(ruta_xml_sin_firmar) or not os.path.isfile(ruta_p12):
        print("Error: El archivo XML o el certificado .p12 no existen.")
        return False

    try:
        # 1. Cargar el certificado .p12
        with open(ruta_p12, "rb") as f_p12:
            p12_data = f_p12.read()

        private_key, certificate, additional_certs = pkcs12.load_key_and_certificates(
            p12_data, 
            contrasena_p12.encode('utf-8'), 
            backend=default_backend()
        )

        if not private_key or not certificate:
            print("Error: No se pudo obtener la clave privada o el certificado.")
            return False

        # 2. Leer archivo XML
        parser = etree.XMLParser(remove_blank_text=True)
        xml_tree = etree.parse(ruta_xml_sin_firmar, parser)
        root = xml_tree.getroot()

        # 3. Canonicalizar el contenido del XML para calcular el Digest
        xml_c14n = etree.tostring(root, method="c14n")
        
        digest = hashes.Hash(hashes.SHA1(), backend=default_backend())
        digest.update(xml_c14n)
        xml_digest_base64 = base64.b64encode(digest.finalize()).decode('utf-8')

        # 4. Construir la estructura SignedInfo
        ns_ds = "http://www.w3.org/2000/09/xmldsig#"
        signed_info = etree.Element(f"{{{ns_ds}}}SignedInfo")
        
        c14n_method = etree.SubElement(signed_info, f"{{{ns_ds}}}CanonicalizationMethod", Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315")
        signature_method = etree.SubElement(signed_info, f"{{{ns_ds}}}SignatureMethod", Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1")
        
        reference = etree.SubElement(signed_info, f"{{{ns_ds}}}Reference", URI="")
        transforms = etree.SubElement(reference, f"{{{ns_ds}}}Transforms")
        etree.SubElement(transforms, f"{{{ns_ds}}}Transform", Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature")
        
        digest_method = etree.SubElement(reference, f"{{{ns_ds}}}DigestMethod", Algorithm="http://www.w3.org/2000/09/xmldsig#sha1")
        digest_value = etree.SubElement(reference, f"{{{ns_ds}}}DigestValue")
        digest_value.text = xml_digest_base64

        # 5. Firmar el bloque SignedInfo con la clave privada
        signed_info_c14n = etree.tostring(signed_info, method="c14n")
        signature_bytes = private_key.sign(
            signed_info_c14n,
            padding.PKCS1v15(),
            hashes.SHA1()
        )
        signature_base64 = base64.b64encode(signature_bytes).decode('utf-8')

        # 6. Armar el nodo final de Firma <Signature>
        signature_node = etree.Element(f"{{{ns_ds}}}Signature", attrib={"Id": "SignatureSRI"})
        signature_node.append(signed_info)
        
        sig_val = etree.SubElement(signature_node, f"{{{ns_ds}}}SignatureValue")
        sig_val.text = signature_base64

        # Agregar KeyInfo con el certificado público
        key_info = etree.SubElement(signature_node, f"{{{ns_ds}}}KeyInfo")
        x509_data = etree.SubElement(key_info, f"{{{ns_ds}}}X509Data")
        x509_cert = etree.SubElement(x509_data, f"{{{ns_ds}}}X509Certificate")
        
        # Extraer bytes DER del certificado exportado (AQUÍ ESTABA EL ERROR DE LA IMPORTACIÓN)
        cert_der = certificate.public_bytes(serialization.Encoding.DER)
        x509_cert.text = base64.b64encode(cert_der).decode('utf-8')

        root.append(signature_node)

        # 7. Guardar el archivo firmado
        carpeta_salida = os.path.dirname(ruta_xml_salida)
        if carpeta_salida:
            os.makedirs(carpeta_salida, exist_ok=True)

        xml_tree.write(ruta_xml_salida, encoding="utf-8", xml_declaration=True, pretty_print=True)
        print(f"¡XML firmado exitosamente! Guardado en: {ruta_xml_salida}")
        return True

    except Exception as e:
        print(f"Error al firmar el documento XML: {e}")
        return False

# --- EJEMPLO DE USO ---
if __name__ == "__main__":

    ruta_xml_original = "C:\\xampp\\htdocs\\deep_script\\php\\comprobantes\\entidades\\entidad_6\\CE6\\FACTURAS\\Generados\\"
    ruta_xml_firmado = "C:\\xampp\\htdocs\\deep_script\\php\\comprobantes\\entidades\\entidad_6\\CE6\\FACTURAS\\Firmados\\"

    clave_acceso = "2209202601070216417900110010030000020141234567818"
    archivo_p12 = "C:\\xampp\\htdocs\\diskcoversystem2\\php\\comprobantes\\certificados\\WALTER_JALIL_VACA_PRIETO_0702164179_2025_09_10_5.p12"
    clave_p12 = "Dlcjvl1210"


    archivo_xml_original = ruta_xml_original+clave_acceso+".xml"
    archivo_xml_firmado = ruta_xml_firmado+clave_acceso+".xml"
    

    # Ejecutar la firma
    exito = firmar_xml_sri(
        ruta_xml_sin_firmar=archivo_xml_original,
        ruta_p12=archivo_p12,
        contrasena_p12=clave_p12,
        ruta_xml_salida=archivo_xml_firmado
    )