# -*- coding: utf-8 -*-
import datetime

import re
import json
from urllib.parse import unquote
import scrapy
from spider.items import FileItem, UserItem
from scrapy_redis.spiders import RedisSpider


class BaidupanSpider(RedisSpider):
    name = 'baidupan'

    def make_request_from_data(self, data):
        url = data.decode('utf-8')
        return scrapy.Request(url, dont_filter=False)

    def parse(self, response):
        if response.request.meta.get('redirect_urls'):
            yield self.process_error(response)
            return
        try:
            yield from self.parse_data(response)
        except:
            pass

    @staticmethod
    def parse_data(response):
        pattern = r'window.yunData = ([\s\S]*?});'
        data = json.loads(re.search(pattern, response.text).group(1))
        files = data.get("file_list", [])
        if len(files) < 1:
            return 0
        file = files[0]

        yield FileItem(
            url=response.url,
            fs_id=file["fs_id"],
            server_filename=file["server_filename"],
            size=int(file['size']),
            server_mtime=int(file["server_mtime"]),
            server_ctime=int(file["server_ctime"]),
            local_mtime=int(file["local_mtime"]),
            local_ctime=int(file["local_ctime"]),
            isdir=int(file["isdir"]),
            isdelete=int(file["isdelete"]),
            status=int(file["status"]),
            category=int(file["category"]),
            share=int(file["category"]),
            path_md5=file["path_md5"],
            path=file["path"],
            parent_path=unquote(file["parent_path"]),
            md5=file["md5"],
            thumbs=file.get("thumbs"),
            dCnt=int(data["dCnt"]),
            ctime=int(data["ctime"]),
            expiredType=data["expiredType"],
            expires=int(data["ctime"]) + data["expiredType"] if data["expiredType"] > 0 else 0,
            sharesuk=data["sharesuk"],
            shareid=data["shareid"],
            pansuk=data["pansuk"],
            uk=data["uinfo"]['uk'],
            last_updated=datetime.datetime.utcnow()
        )

        yield UserItem(
            uname=data["uinfo"]['uname'],
            avatar_url=data["uinfo"]['avatar_url'],
            uk=data["uinfo"]['uk'],
            third=data["uinfo"]['third'],
            relation_type=data["uinfo"]['relation_type'],
            last_updated=datetime.datetime.utcnow()
        )

    @staticmethod
    def process_error(response):
        if "error.html" in response.url:
            # TODO: 404
            pass
        elif "wap/error" in response.url:
            # TODO: 分享已取消或删除或过期
            pass
        elif "wap/init" in response.url:
            # TODO: 密码输入
            pass
        else:
            pass
