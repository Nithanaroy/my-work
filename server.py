import cherrypy, os

class Root:
    @cherrypy.expose
    def index(self):
        template = os.path.abspath('index.html')
        return open(template)
    
    @cherrypy.expose
    def upload(self, data):
        print 'In upload function'
        file_path = 'static/images/'
        if data:
            print data
        fd = os.open(file_path + cherrypy.request.headers["X-File-Name"], os.O_WRONLY | os.O_CREAT)
        os.write(fd, cherrypy.request.body.read())
        os.close(fd)
        return "Successful call"
    
root = Root()

cherrypy.tree.mount(root, '/', 'app.conf')
cherrypy.engine.start()
cherrypy.engine.block()
