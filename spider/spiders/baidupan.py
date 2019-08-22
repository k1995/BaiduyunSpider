# -*- coding: utf-8 -*-
import datetime
import json
import logging
import re
import traceback

import scrapy
from scrapy_redis.spiders import RedisSpider

from spider.items import FileItem, UserItem
from utils import get_shortkey


class BaidupanSpider(RedisSpider):
    name = 'baidupan'

    def make_request_from_data(self, data):
        url = data.decode('utf-8')
        key = get_shortkey(url)
        url = "https://pan.baidu.com/api/shorturlinfo?web=5&app_id=250528&clienttype=5&shorturl={}".format(key)
        return scrapy.Request(url, dont_filter=False, meta={"shorturl": key})

    def parse(self, response):
        try:
            data = json.loads(response.text)
            if data["errno"] == -3:
                url = "https://pan.baidu.com/share/list?web=5&app_id=250528&channel=chunlei&clienttype=5&desc=1" \
                      "&showempty=0&num=200&order=time&root=1&shorturl={}".format(response.meta['shorturl'][1:])
                meta = {
                    "shorturl": data['shorturl'],
                    "ctime": data['ctime'],
                    "expiredType": data['expiredtype'],
                    "share_username": data['share_username'],
                    "share_photo": data["share_photo"]
                }
                yield scrapy.Request(url, dont_filter=False, callback=self.parse_data, meta=meta)
            elif data["errno"] == -21:
                logging.info("分享已取消 %s", response.url)
            elif data["errno"] == -9:
                logging.info("私密分享，开源版暂不支持 %s", response.url)
            elif data["errno"] == 105 or data["errno"] == 2:
                logging.info("分享链接不对 %s", response.url)
            else:
                logging.error("未知错误 errno: {}, url: {}", data["errno"], response.url)
        except:
            logging.error("解析错误 %s", response.url)
            traceback.print_exc()

    def parse_data(self, response):
        try:
            data = json.loads(response.text)
            if data['errno'] != 0:
                logging.error("数据接口错误，errno: {}, url: {}", data["errno"], response.url)
                return

            for file in data['list']:
                yield FileItem(
                    url=response.meta['shorturl'],
                    fs_id=file["fs_id"],
                    server_filename=file["server_filename"],
                    size=int(file['size']),
                    server_mtime=int(file["server_mtime"]),
                    server_ctime=int(file["server_ctime"]),
                    local_mtime=int(file["local_mtime"]),
                    local_ctime=int(file["local_ctime"]),
                    isdir=int(file["isdir"]),
                    category=int(file["category"]),
                    path=file["path"],
                    md5=file["md5"],
                    thumbs=file.get("thumbs"),
                    ctime=response.meta['ctime'],
                    expiredType=response.meta['expiredType'],
                    expires=response.meta['ctime'] + response.meta['expiredType'] if response.meta['expiredType'] > 0 else 0,
                    shareid=data["share_id"],
                    uk=data["uk"],
                    last_updated=datetime.datetime.utcnow()
                )

            yield UserItem(
                uname=response.meta['share_username'],
                avatar_url=response.meta['share_photo'],
                uk=data["uk"],
                last_updated=datetime.datetime.utcnow()
            )
        except:
            logging.error("数据解析错误 %s", response.url)
            traceback.print_exc()
