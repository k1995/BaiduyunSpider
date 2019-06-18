from urllib import parse


def normalize_shareurl(url):
    base_url = "https://pan.baidu.com"
    u = parse.urlparse(url)
    if u.path.startswith('/s/'):
        return "{}{}".format(base_url, u.path)
    elif u.path.startswith('/share/link'):
        query = parse.parse_qs(u.query)
        return "{}/share/link?shareid={}&uk={}".format(base_url, query['shareid'][0], query['uk'][0])
    raise Exception("URL格式错误")

print(normalize_shareurl("https://pan.baidu.com/share/link?shareid=2101811667&uk=3660825403&zz1"))
print(normalize_shareurl("https://pan.baidu.com/s/1GxTj91_RT50aIaSG3BmcSA?fid=735147087821718"))