## Introduction

This is a streamlit component derived from `streamlit_drawable_canvas` to select and modify image labels

## For User

1. Copy the folder sl_comp and put it at same level as your application main code (**Note** You do not need to install streamlit_drawable_canvas)

2. Refer to `app.py` function `gbr(...)` on example usage

---

## For Developer

<path-to>/sl_comp/sdc/streamit_drawable_canvas/__init__.py

_RELEASE = False

- need to run npm start at ./src/frontend folder
- can edit code and view frontend effects on thefly
- run npm build to compile the program to static assets (no need npm start anymore)

_RELEASE = True

- see above
