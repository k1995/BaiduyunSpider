from flask import Flask, jsonify, request
from pymongo import MongoClient
from spider import settings
from utils.mongoflask import MongoJSONEncoder, ObjectIdConverter

# Flask
app = Flask(__name__)
app.json_encoder = MongoJSONEncoder
app.url_map.converters['objectid'] = ObjectIdConverter

# Mongo
mongo = MongoClient(settings.MONGO_URI)
db = mongo['baidupan']
files = db.share_files
users = db.share_users

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


def get_offset():
    page = int(request.args.get('page', 1))
    return (page - 1) * page_size


app.run()
