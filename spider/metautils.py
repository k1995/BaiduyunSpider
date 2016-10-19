#coding: utf8
import os
import binascii

"""
/*
 *--------------------------------------------       
 *
 *  本程序由 [Github 中文社区](http://www.githubs.cn/)发布
 *        
 *  Github 仓库: https://github.com/k1995/BaiduyunSpider
 * 
 *  安装教程：http://www.githubs.cn/post/22
 *
 *  疑问？解答：http://www.githubs.cn/topic/118
 * ----------------------------------------*/
"""

cats = {
    u'video': u'视频',
    u'image': u'图片',
    u'document': u'书籍',
    u'music': u'音乐',
    u'package': u'压缩',
    u'software': u'软件',
}

def get_label(name):
    if name in cats:
        return cats[name]
    return u'其它'

def get_label_by_crc32(n):
    for k in cats:
        if binascii.crc32(k)&0xFFFFFFFFL == n:
            return k
    return u'other'

def get_extension(name):
    return os.path.splitext(name)[1]

def get_category(ext):
    ext = ext + '.'
    cats = {
        u'video': '.avi.mp4.rmvb.m2ts.wmv.mkv.flv.qmv.rm.mov.vob.asf.3gp.mpg.mpeg.m4v.f4v.',
        u'image': '.jpg.bmp.jpeg.png.gif.tiff.',
        u'document': '.pdf.isz.chm.txt.epub.bc!.doc.docx.xlsx.xls.pptx.ppt.',
        u'music': '.mp3.wma.ape.wav.dts.mdf.flac.',
        u'package': '.zip.rar.7z.tar.gz.iso.dmg.pkg.',
        u'software': '.exe.app.msi.apk.',
        u'torrent': '.torrent.'
    }
    for k, v in cats.iteritems():
        if ext in v:
            return k
    return u'other'

def get_detail(y):
    if y.get('files'):
        y['files'] = [z for z in y['files'] if not z['path'].startswith('_')]
    else:
        y['files'] = [{'path': y['name'], 'length': y['length']}]
    y['files'].sort(key=lambda z:z['length'], reverse=True)
    bigfname = y['files'][0]['path']
    ext = get_extension(bigfname).lower()
    y['category'] = get_category(ext)
    y['extension'] = ext


