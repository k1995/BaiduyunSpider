import sys
import os


try:
    sys.path.append("..")
    from flask import Flask, jsonify, request, send_from_directory
    from pymongo import MongoClient
    from spider import settings
    from utils.mongoflask import MongoJSONEncoder, ObjectIdConverter
    from utils import normalize_shareurl
    from redis import StrictRedis
except Exception as e:
    raise e

# Flask
app = Flask(__name__, static_folder='../web/admin/build/')
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
    try:
        url = normalize_shareurl(request.form.get('url'))
        redis.lpush(queue_key, url)
        return "ok"
    except Exception as e:
        return repr(e)


# Serve React App
@app.route('/', defaults={'path': ''})
@app.route('/<path:path>')
def serve(path):
    if path != "" and os.path.exists(app.static_folder + path):
        return send_from_directory(app.static_folder, path)
    else:
        return send_from_directory(app.static_folder, 'index.html')


def get_offset(size):
    page = int(request.args.get('page', 1))
    return (page - 1) * size


def run():
    app.run(use_reloader=True, )


if __name__ == "__main__":
    run()
