# -*- coding: utf-8 -*-
import scrapy
from scrapy.utils.response import open_in_browser
from spider import settings
from scrapy.crawler import CrawlerProcess


class TestSpider(scrapy.Spider):
    name = 'test'
    start_urls = ['https://pan.baidu.com/s/1ve2t_X9P8sssYnwFVJ36og']

    def parse(self, response):
        open_in_browser(response)


process = CrawlerProcess({
    'USER_AGENT': settings.USER_AGENT
})

process.crawl(TestSpider)
process.start()
