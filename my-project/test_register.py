import urllib.request
import urllib.parse
import re

print("Fetching register page...")
req = urllib.request.Request("http://localhost:8001/register")
try:
    with urllib.request.urlopen(req) as response:
        html = response.read().decode('utf-8')
        cookies = response.headers.get_all('Set-Cookie') or []
        session_cookies = []
        for c in cookies:
            session_cookies.append(c.split(';')[0])
        cookie_header = "; ".join(session_cookies)
        
        csrf_token = re.search(r'name="csrf-token" content="(.*?)"', html)
        if csrf_token:
            csrf = csrf_token.group(1)
        else:
            print("CSRF Token not found!")
            exit(1)
            
        print("Sending POST request to /register...")
        data = urllib.parse.urlencode({
            '_token': csrf,
            'name': 'Test User',
            'email': 'newuser123@example.com',
            'password': 'password123',
            'password_confirmation': 'password123'
        }).encode('utf-8')
        
        class NoRedirect(urllib.request.HTTPRedirectHandler):
            def redirect_request(self, req, fp, code, msg, headers, newurl):
                return None
                
        opener = urllib.request.build_opener(NoRedirect())
        
        post_req = urllib.request.Request("http://localhost:8001/register", data=data, method='POST')
        post_req.add_header('Cookie', cookie_header)
        post_req.add_header('Content-Type', 'application/x-www-form-urlencoded')
        post_req.add_header('Referer', 'http://localhost:8001/register')
        
        try:
            post_response = opener.open(post_req)
            print(f"POST /register Status: {post_response.status}")
        except urllib.error.HTTPError as e:
            print(f"POST /register Error Status: {e.code}")
            print(f"Location header: {e.headers.get('Location')}")
            
except Exception as e:
    print(f"Error: {e}")
