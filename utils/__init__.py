from urllib import parse
import logging


def normalize_shareurl(url):
    base_url = "https://pan.baidu.com"
    u = parse.urlparse(url)
    if u.path.startswith('/s/'):
        return "{}{}".format(base_url, u.path)
    logging.error("Bad url: {}", url)
    raise Exception("URL格式错误")


def get_shortkey(url):
    u = parse.urlparse(url)
    if u.path.startswith('/s/'):
        return u.path.replace('/s/', '')
    logging.error("Bad url: {}", url)
    raise Exception("URL格式错误")
