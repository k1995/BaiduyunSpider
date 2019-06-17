# -*- coding: utf-8 -*-
import datetime
import logging

import re
import json
import traceback
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
            yield from self.process_redirect(response)
            return
        try:
            yield from self.parse_data(response)
        except:
            logging.error("解析错误 %s", response.url)
            traceback.print_exc()

    def parse_data(self, response):
        pattern = r'window.yunData = ([\s\S]*?});'
        data = json.loads(re.search(pattern, response.text).group(1))
        files = data.get("file_list", [])
        if len(files) < 1:
            logging.error("len(files) < 1 %s", response.url)
            return

        for file in files:
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

    def process_redirect(self, response):
        origin_url = response.request.meta.get('redirect_urls')[0]
        if "error.html" in response.url:
            logging.info("404 %s", origin_url)
        elif "wap/error" in response.url:
            logging.info("分享已取消或过期 %s", origin_url)
        elif "wap/init" in response.url:
            logging.info("开源版本暂不支持私密分享 %s", origin_url)
        else:
            logging.info("Unknown 302 %s => %s", origin_url, response.url)
