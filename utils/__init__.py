from urllib import parse


def normalize_shareurl(url):
    base_url = "https://pan.baidu.com"
    u = parse.urlparse(url)
    if u.path.startswith('/s/'):
        return "{}/s/{}".format(base_url, u.path)
    elif u.path.startswith('/share/link'):
        query = parse.parse_qs(u.query)
        return "{}/share/link?shareid={}&uk={}".format(base_url, query.shareid, query.uk)
    raise Exception("URL格式错误")
