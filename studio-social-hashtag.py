import csv
import re
import sys
import json
import psycopg2
import psycopg2.extras
from wordcloud import WordCloud, STOPWORDS, ImageColorGenerator
import nltk
import unicodedata
nltk.download('stopwords')

id_wordcloud_text = sys.argv[1]
file_name = sys.argv[2]
tipo = sys.argv[3]
cliente = sys.argv[4]
        
stopwords = ''

f = open ("/home/socialstudio/public_html/storage/app/hashtag/files/"+file_name+".json", "r")
wordcloud_words = json.loads(f.read())

if tipo == 'imagem' :
    if len(wordcloud_words) > 0:
        wordcloud = WordCloud(width = 3000, height = 2000, random_state=1, background_color='white', colormap='Set2', stopwords = stopwords).generate_from_frequencies(wordcloud_words)
        wordcloud.to_file("/home/socialstudio/public_html/storage/app/hashtag/files/"+file_name+".png")
    
print('END')