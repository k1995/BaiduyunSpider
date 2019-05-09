from flask import Flask, jsonify, request
from pymongo import MongoClient
from spider import settings
from utils.mongoflask import MongoJSONEncoder, ObjectIdConverter
from flask_cors import CORS
from redis import StrictRedis

# Flask
app = Flask(__name__)
CORS(app)
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
page_size = 20


@app.route("/share_files")
def share_files():
    items = files.find().skip(get_offset()).limit(page_size)
    count = files.count()
    return jsonify({
        'total': count,
        'has_more': get_offset() + page_size < count,
        'items': list(items)
    })


@app.route("/share_users")
def share_users():
    items = users.find().skip(get_offset()).limit(page_size)
    count = users.count()
    return jsonify({
        'total': count,
        'has_more': get_offset() + page_size < count,
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


def get_offset():
    page = int(request.args.get('page', 1))
    return (page - 1) * page_size


app.run()
