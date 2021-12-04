import csv
import re
import json
import psycopg2
import psycopg2.extras
import numpy as np
import pandas as pd
import nltk
from nltk.tokenize import TweetTokenizer
from sklearn.feature_extraction.text import CountVectorizer
from sklearn.naive_bayes import MultinomialNB

nltk.download('stopwords')

df = pd.read_csv('storage/app/sentiment/base_de_testes_limpa.csv', sep=";")
tweets = df['mensagem']
classes = df['sentimento']
classes = np.array(classes.values.tolist())

def Preprocessing(instancia):
    instancia = re.sub(r"http\S+", "", instancia).lower().replace('.','').replace(';','').replace('-','').replace(':','').replace(')','').replace('"','')
    stopwords = set(nltk.corpus.stopwords.words('portuguese'))
    palavras = [i for i in instancia.split() if not i in stopwords]
    return (" ".join(palavras))

tweets = [Preprocessing(i) for i in tweets]

tweet_tokenizer = TweetTokenizer() 
vectorizer = CountVectorizer(analyzer="word", tokenizer=tweet_tokenizer.tokenize)
freq_tweets = vectorizer.fit_transform(tweets)

modelo = MultinomialNB()
modelo.fit(freq_tweets,classes)

#con = psycopg2.connect(host='162.241.40.125', database='studiosocial',user='postgres', password='DMK@rr19')
con = psycopg2.connect(host='localhost', database='studiosocial',user='postgres', password='cipplp10')
cur = con.cursor(cursor_factory = psycopg2.extras.RealDictCursor)

sql = 'select * from media_twitter'

cur.execute(sql)
medias = cur.fetchall()

for media in medias:

    texto = [media['full_text']]
    freq_testes = vectorizer.transform(texto)

    for t, c in zip (texto,modelo.predict(freq_testes)):
        print (t +", "+ c) 
        if c == 'neutro' : sent = 0
        if c == 'positivo' : sent = 1
        if c == 'negativo' : sent = -1

    sql = 'update media_twitter SET sentiment = '+str(sent)+' WHERE twitter_id = '+str(media['twitter_id'])
    cur.execute(sql)
    con.commit() 