import os, cherrypy, simplejson

class Root:
    @cherrypy.expose
    def index(self):
        template = os.path.abspath('index_server_chunk.html')
        return open(template)
    
    @cherrypy.expose
    def upload(self, myFile):
        '''
        Uploads the passed file
        '''
        CHUNK = 1024 # * 1024
        read_length = 0
        file_path = 'static/images/'
        fd = open(file_path + cherrypy.request.headers['Name'], 'a+')
        while True:
            data = myFile.file.read(CHUNK)
            if not data:
                break
            fd.write(data)
            read_length += len(data)
        fd.close()
        return 'Success!!'
    
    @cherrypy.expose
    def check_upload(self):
        '''
        checks if the given file has already uploaded
        TODO: Better way to check if the file has uploaded. For now using filename 
        '''
        cl = cherrypy.request.headers['Content-Length']
        rawbody = cherrypy.request.body.read(int(cl))
        filekeys = simplejson.loads(rawbody)
        file_size = {}
        images_path = 'static/images/'
        for key in filekeys:
            file_path = os.path.join(images_path, self.get_file_name_from_key(key))
            file_size[key] = 0
            if os.path.isfile(file_path):
                file_size[key] = int(os.path.getsize(file_path)) 
        return simplejson.JSONEncoder().encode(file_size)
    
    def get_file_name_from_key(self, key):
        '''
            Gets the filename from key. Currently filename itself is the key
            TODO: A better file key
        '''
        return key
    
cherrypy.tree.mount(Root(), '/', 'app.conf')
cherrypy.engine.start()
cherrypy.engine.block()