from PIL import Image
import json
import streamlit as st

from sl_comp.sdc.streamlit_drawable_canvas import st_canvas

def gbr(
    INPUT_IMAGE_FILE = 'img/ftnc9i4gm900xwx967n.jpg',
    INPUT_JSON_FILE = 'img/ftnc9i4gm900xwx967n.json',
    # INPUT_JSON_FILE = 'img/ftnc9i4gm900xwx967n-overlap.json',

    OUTPUT_JSON_FILE = 'img/ftnc9i4gm900xwx967n.json',
    ## OTHER CONFIGURATIONS- SET THEM HERE
    CANVAS_SIZE = 256,
    CAT_LIST_FILEPATH = 'category_data/newcat_colour_mapping.json',
    CAT_LIST_LEGEND_FOLDER = 'category_data'
    ):
    # LOCAL FUNCTIONS
    def update_cat():
        st.session_state.cat = st.session_state.select_cat

    # State setting
    if 'cat' not in st.session_state:
        st.session_state.cat = 'uncategorised'

    # st.write('BEFORE', st.session_state.cat)

    # Load the categories
    cat_list = []
    with open(CAT_LIST_FILEPATH) as json_file:
        cat_list = json.load(json_file)
    # Map to color
    catid_color_map = { }
    # Map to count
    catid_count_map = { }
    # Initialize the maps
    for cat in cat_list:
        catid_color_map[cat["catid"]] = cat["hex"]
        catid_count_map[cat["catid"]] = 0

    # Extract image
    bg_image = Image.open(INPUT_IMAGE_FILE)
    bg_image_w, bg_image_h = bg_image.size # TBD clip or reject if image is too big
    # st.write(bg_image_w, bg_image_h)

    # Load the category - color mapping file
    f = open(INPUT_JSON_FILE)
    all_cat = json.load(f)
    f.close()

    polygon_template = {
        "type": "path",
        "version": "4.4.0",
        "originX": "center",
        "originY": "center",
        "fill": "rgba(255, 255, 255, 0.1)",
        # "stroke": "#FF0000",
        # "path": []
        "strokeWidth": 3,
        "strokeDashArray": None,
        "strokeLineCap": "butt",
        "strokeDashOffset": 0,
        "strokeLineJoin": "miter",
        "strokeUniform": False,
        "strokeMiterLimit": 4,
        "scaleX": 1,
        "scaleY": 1,
        "angle": 0,
        "flipX": False,
        "flipY": False,
        "opacity": 1,
        "shadow": None,
        "visible": True,
        "backgroundColor": "",
        "fillRule": "nonzero",
        "paintFirst": "fill",
        "globalCompositeOperation": "source-over",
        "skewX": 0,
        "skewY": 0,
        "hasBorders": False,
        "hasControls": False,
        "perPixelTargetFind": True,
        "targetFindTolerance": 1,
    }

    # selected category json
    sel_cat_json = {
        "version": "4.4.0",
        "objects": [],
        "background": "#eee"
    }

    # set selected to -1
    sel_cat_json['selected'] = -1
    # print(sel_cat_json)

    for one_cat in all_cat:
        # one_cat['coordinates']
        cat_id = one_cat['id']
        catid_color_map[cat_id] = one_cat['colour']
        # print(catid_count_map[cat_id])
        catid_count_map[cat_id]+=1

        if (cat_id == st.session_state.cat):
            new_object = polygon_template.copy()
            new_object['path'] = one_cat['coordinates']
            new_object['stroke'] = one_cat['colour']
            sel_cat_json['objects'].append(new_object)

    catid_list = list(catid_color_map.keys())

    catid_mod_list = catid_list.copy()
    # catid_mod_list.remove(st.session_state.cat) # do not remove...
    # print('CATID_COLOR MAP', catid_color_map)
    # print('CATID LIST', catid_list)
    # print('cat_map', cat_map)


    container = st.container()
    col1, col2, col3 = container.columns([4,1,1])
    with col1:
        st.subheader('Image Label Editor')
        # if (bg_image_w > 100):
        #     st.write(f'{bg_image_w} px image too wide, max 100px')
        #     return
        # if (bg_image_h > 100):
        #     st.write(f'{bg_image_w} px image too wide, max 100px')
        #     return
        canvas_result = st_canvas(
            # fill_color=label_color,
            initial_drawing=sel_cat_json,
            stroke_width=3,
            background_image=bg_image,
            height=bg_image_h,
            width=bg_image_w,
            drawing_mode="select",
            update_streamlit=True,
            display_toolbar=False,
            key="gbr",
        )
    with col2:
        ## TO UPDATE AND CHANGES
        def update_cat2():
            st.session_state.cat = catid_list[st.session_state.select_cat]

        st.subheader('Select Category')
        res2 = st.radio(
            label="",
            key="select_cat",
            # options=catid_list,
            # on_change=update_cat,
            options= list(range(len(catid_list))),
            format_func=lambda x: f"Detected: {catid_count_map[catid_list[x]]}",
            on_change=update_cat2
        )
        st.session_state.cat = catid_list[res2]
    with col3:
        st.subheader('Please Label')
        with st.form(key="label-shape", clear_on_submit=False):
            modify_all = st.checkbox('Modify All')
            modified_catid = st.radio(
                label="",
                key="select_cat_to_mod",
                options=catid_mod_list
            )
            modify = st.form_submit_button('Modify')

    st.button('Clear Selection')

    ## LEGEND
    st.subheader('Category Legend')
    cat_len = len(catid_list)
    legend = st.columns(cat_len)
    for i in range(cat_len):
        fname = f"{CAT_LIST_LEGEND_FOLDER}/cat{i}.jpg"
        # fname = "img/cat0.jpg"
        legend[i].image( Image.open(fname).resize((64, 64)) )

    # st.write('modify button pressed?', modify)
    # st.write('MODIFY ALL', modify_all)
    st.write('MODIFY: ', st.session_state.cat, ' --> ', modified_catid)

    if (modify):
        try:
            selected = canvas_result.json_data['selected']
        except:
            selected = -2
        st.write('SELECTED INDEX', selected)
        if (modify_all or selected > -1):

            modified_catcolor = catid_color_map[modified_catid]

            # loop through and replace all or replace selected index
            found_index = 0
            for one_cat in all_cat:
                # if (selected > -1):
                cat_id = one_cat['id']
                if (cat_id == st.session_state.cat):
                    one_cat['colour'] = modified_catcolor
                    one_cat['id'] = modified_catid
                    if (selected > -1 and selected == found_index):
                        break
                    found_index += 1
            # print('MODIFIED', all_cat)

            with open(OUTPUT_JSON_FILE, 'w') as outfile:
                json.dump(all_cat, outfile)

if __name__ == "__main__":
    st.set_page_config(
        page_title="Great Barrier Reef Project", page_icon=":pencil2:", layout="wide"
    )
    gbr()