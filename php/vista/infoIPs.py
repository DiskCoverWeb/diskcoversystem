import requests
import socket
import json
import platform
import subprocess
 
def getWanIP():
    return requests.get('https://api.ipify.org').text
    
def getLocalIP():
    return socket.gethostbyname(socket.gethostname())
    
def getPcName():
    return socket.gethostname()

def getMac():
    sistema_operativo = platform.system()
    if sistema_operativo == "Windows":
        try:
            result = subprocess.check_output(["getmac"])
            result = result.decode("utf-8", errors="ignore")
            mac_adress = result.split("\n")[3].split()[0]
            return mac_adress
        except Exception as e:
            return str(e)
    elif sistema_operativo == "Linux":
        try:
            result = subprocess.check_output(["ifconfig"])
            result = result.decode("utf-8", errors="ignore")
            mac_adress = (result.split("HWaddr")[1]).split("\n")[0]
            return mac_adress
        except Exception as e:
            return str(e)
    else:
        return "Sistema Operativo no Compatible"
            
            
    

def get_data():
    #request_url = request.url
    data = {
        'wan_ip': getWanIP(),
        'local_ip': getLocalIP(),
        'pc_name': getPcName(),
        'mac': getMac() 
    }
    return data

print(get_data())
