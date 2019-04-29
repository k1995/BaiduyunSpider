from pymongo import MongoClient

from spider.items import FileItem, UserItem


class SpiderPipeline(object):
    def __init__(self, settings):
        client = MongoClient(settings.get('MONGO_URI'))
        self.db = client['baidupan']
        self.files = self.db.share_files
        self.users = self.db.share_users

    @classmethod
    def from_crawler(cls, crawler):
        return cls(crawler.settings)

    def process_item(self, item, spider):
        # File info
        if isinstance(item, FileItem):
            self.files.update_one(
                {'fs_id': item['fs_id']},
                {'$set': item},
                True)
        # User info
        if isinstance(item, UserItem):
            self.users.update_one(
                {'uk': item['uk']},
                {'$set': item},
                True)
        return item
