from flask import Flask, jsonify, request
from pymongo import MongoClient
from spider import settings
from utils.mongoflask import MongoJSONEncoder, ObjectIdConverter
from redis import StrictRedis

# Flask
app = Flask(__name__)
app.json_encoder = MongoJSONEncoder
app.url_map.converters['objectid'] = ObjectIdConverter

# Mongo
mongo = MongoClient(settings.MONGO_URI)
db = mongo['baidupan']
files = db.share_files
users = db.share_users

# Redis
redis = StrictRedis.from_url(settings.REDIS_URL)

# Common settings
default_page_size = 10


@app.route("/share_files")
def share_files():
    size = int(request.args.get('size', default_page_size))
    items = files.find().skip(get_offset(size)).limit(size)
    count = files.count()
    return jsonify({
        'total': count,
        'has_more': get_offset(size) + size < count,
        'items': list(items)
    })


@app.route("/share_users")
def share_users():
    size = int(request.args.get('size', default_page_size))
    items = users.find().skip(get_offset(size)).limit(size)
    count = users.count()
    return jsonify({
        'total': count,
        'has_more': get_offset(size) + size < count,
        'items': list(items)
    })


@app.route("/addUrl", methods=['POST'])
def add_url():
    from spider.spiders.baidupan import BaidupanSpider
    queue_key = BaidupanSpider.name + ":start_urls"
    url = request.form.get('url')
    if url is None or not url.startswith("https://pan.baidu.com/s/"):
        return "URL格式不对"
    try:
        redis.lpush(queue_key, url)
        return "ok"
    except Exception as e:
        return repr(e)


def get_offset(size):
    page = int(request.args.get('page', 1))
    return (page - 1) * size


app.run()
