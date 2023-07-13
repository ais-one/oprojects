import streamlit as st

animals = {}

def test():
    cat = 'aa'

    try:
        animals[cat]
    except:
        animals[cat] = {
            'name': 'hh',
            'age': 2 
        }

test()

st.write('aaaa')
st.write(animals)