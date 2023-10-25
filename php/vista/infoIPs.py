"""
Toca subir la version de python, aunque sea a 3.3
pip install flask
pip install flask-cors
"""
from flask import Flask, jsonify, request
import requests
import socket
from flask_cors import CORS

app = Flask(__name__)
CORS(app, resources={r"/get_data": {"origins": ["https://erp.diskcoversystem.com","https://erp.diskcoversystem.com/~diskcover","http://localhost"]}}) 

def getWanIP():
    response = requests.get("https://api.ipify.org?format=json")
    data = response.json()
    wan_ip = data['ip']
    return wan_ip
    
def getLocalIP():
    return request.remote_addr
    
    
def getPcName():
    return socket.gethostname()
    

@app.route('/get_data', methods=['GET'])
def get_data():
    #request_url = request.url
    data = {
        'wan_ip': getWanIP(),
        'local_ip': getLocalIP(),
        'pc_name': getPcName()
    }
    return jsonify(data)

    

if __name__ == '__main__':
    app.run()