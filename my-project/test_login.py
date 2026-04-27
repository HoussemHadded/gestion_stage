import urllib.request
import urllib.parse
import re

print("Fetching login page to get CSRF token and session cookies...")
req = urllib.request.Request("http://localhost:8001/login")
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
            print(f"CSRF Token found: {csrf[:10]}...")
        else:
            print("CSRF Token not found!")
            exit(1)
            
        print("Sending POST request to /login...")
        data = urllib.parse.urlencode({
            '_token': csrf,
            'email': 'test@example.com',
            'password': 'password'
        }).encode('utf-8')
        
        post_req = urllib.request.Request("http://localhost:8001/login", data=data, method='POST')
        post_req.add_header('Cookie', cookie_header)
        post_req.add_header('Content-Type', 'application/x-www-form-urlencoded')
        post_req.add_header('Referer', 'http://localhost:8001/login')
        
        try:
            with urllib.request.urlopen(post_req) as post_response:
                print(f"POST /login Status: {post_response.status}")
                print(f"Final URL: {post_response.url}")
        except urllib.error.HTTPError as e:
            print(f"POST /login Error Status: {e.code}")
            print(f"Response: {e.read().decode('utf-8')[:500]}")
            
except Exception as e:
    print(f"Error: {e}")
