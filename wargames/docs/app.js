// TBD
// - center table
// - add filter
// - add sorting
// no paging ?

export default {
  template: /*html*/`
    <div class="container" style="width:80vw;">
      <h1 class="title">Wargames Singapore Facebook Page</h1>
      <h2 class="subtitle">Museum Acquisition Listing (latest on top)</h2>
      <div class="table-container" style="height:80vh;overflow-y:auto;">
        <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
          <thead>
            <tr>
              <th class="sticky-th" @click="() => sortTable('title')"><abbr title="Game">Game Title {{ arrow('title') }}</abbr></th>
              <th class="sticky-th">Publisher</th>
              <th class="sticky-th" @click="() => sortTable('post')"><abbr title="Post">Post Link (Chronological) {{ arrow('post') }}</abbr></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(post, index) in posts" :key="index">
              <td>{{post.title}}</td>
              <td>{{post.pub}}</td>
              <td><a :href="'https://www.facebook.com/wargames.singapore/posts/' + post.post" target="_blank">{{ post.post }}</a></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  `,
  data () {
    return {
      posts: [],
      asc: {
        title: true,
        post: true
      },
      lastItem: 'post',
      search: '',
      filteredPosts: [],
    }
  },
  methods: {
    arrow (item) {
      if (item === this.lastItem) return this.asc[item] ? '⇑' : '⇓'
      return ''
    },
    sortTable (_key) {
      if (this.asc[_key] == false)
        this.posts.sort((a, b) => a[_key] < b[_key] ? -1 : (a[_key] > b[_key] ? 1 : 0))
      else
        this.posts.sort((a, b) => a[_key] > b[_key] ? -1 : (a[_key] < b[_key] ? 1 : 0))
      this.asc[_key] = !this.asc[_key]
      this.lastItem = _key
    }
  },
  async mounted() {
    try {
      const res = await fetch('data.json')
      this.posts = await res.json()
      // console.log(this.posts)
      this.sortTable('post')
    } catch (e) {
    }
  }
}