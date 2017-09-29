#!/usr/bin/env python
#coding=utf8

import os, sys, json, httplib, urllib, urlparse, subprocess, time
from BaseHTTPServer import HTTPServer
from BaseHTTPServer import BaseHTTPRequestHandler


class Request:
	def Get(url, port, path):
		httpClient = None
		try:
			httpClient = httplib.HTTPConnection(url, port, timeout=30)
			httpClient.request('GET', path)
			#response是HTTPResponse对象
			response = httpClient.getresponse()
			if response.status <> 200:
				raise Exception(response.reason)
			return response.read()
		finally:
			if httpClient:
				httpClient.close()

	def Post(url, port, path, data):
		httpClient = None
		try:
			params = urllib.urlencode(data)
			headers = {"Content-type": "application/x-www-form-urlencoded", "Accept": "application/json"}

			httpClient = httplib.HTTPConnection(url, port, timeout=30)
			httpClient.request("POST", path, params, headers)

			response = httpClient.getresponse()
			if response.status <> 200:
				raise Exception(response.reason)

			return response.read()
		finally:
			if httpClient:
				httpClient.close()

def RunCommand(command):
	if command == 'status':
		status = os.popen('top -bi -n 2 -d 0.02').read()
		if status == '':
			result = 'unsupport top command'
		else:
			cpu = status.split('\n')[2]
			mem = status.split('\n')[3]
			result = cpu + '@' + mem
	else:
		result = os.popen(command).read()
	return result


class TodoHandler(BaseHTTPRequestHandler):
	TODOS = []
	def do_HEAD(s):
		s.send_response(200)
		s.send_header("Content-type", "application/json")
		s.end_headers()
	def do_GET(s):
		try:
			rs = urlparse.urlparse(s.path)
			query = urlparse.parse_qs(rs.query)
			command = query['command'][0]
			result = RunCommand(command)
			resultStr = json.dumps({'Result': result})
		except BaseException,e:
			resultStr = json.dumps({'Result': repr(e)})

		s.send_response(200)
		s.send_header("Content-type", "application/json")
		s.send_header("Access-Control-Allow-Origin", "*")
		s.end_headers()
		s.wfile.write(resultStr)

	def do_POST(s):
		s.send_response(200)
		s.send_header("Content-type", "text/thml")
		s.end_headers()

try:
	if len(sys.argv) < 2:
		port = 8888
	else:
		port = int(sys.argv[1])
	server = HTTPServer(('0.0.0.0', port), TodoHandler)
	print("Starting server on " + str(port) + ", use <Ctrl-C> to stop")
	server.serve_forever()
except BaseException,e:
	print e