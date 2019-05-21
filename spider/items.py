# -*- coding: utf-8 -*-

# Define here the models for your scraped items
#
# See documentation in:
# https://doc.scrapy.org/en/latest/topics/items.html

import scrapy


class FileItem(scrapy.Item):
    url = scrapy.Field()
    fs_id = scrapy.Field()
    server_filename = scrapy.Field()
    size = scrapy.Field()
    server_mtime = scrapy.Field()
    server_ctime = scrapy.Field()
    local_mtime = scrapy.Field()
    local_ctime = scrapy.Field()
    isdir = scrapy.Field()
    isdelete = scrapy.Field()
    status = scrapy.Field()
    category = scrapy.Field()
    share = scrapy.Field()
    path_md5 = scrapy.Field()
    path = scrapy.Field()
    parent_path = scrapy.Field()
    md5 = scrapy.Field()
    thumbs = scrapy.Field()
    dCnt = scrapy.Field()
    ctime = scrapy.Field()
    expiredType = scrapy.Field()
    expires = scrapy.Field()
    sharesuk = scrapy.Field()
    shareid = scrapy.Field()
    pansuk = scrapy.Field()
    uk = scrapy.Field()
    last_updated = scrapy.Field()


class UserItem(scrapy.Item):
    uname = scrapy.Field()
    avatar_url = scrapy.Field()
    uk = scrapy.Field()
    third = scrapy.Field()
    relation_type = scrapy.Field()
    last_updated = scrapy.Field()

