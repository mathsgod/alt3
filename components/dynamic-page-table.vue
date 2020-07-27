<template>
  <table class="table">
    <thead>
      <tr>
        <td>
          <el-button size="mini" type="primary" icon="fa fa-plus" @click="onClickAdd"></el-button>
        </td>
        <th v-for="(b,index) in structure" v-text="b.name" :key="index"></th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(d,i) in data" :key="i">
        <td>
          <el-button size="mini" type="danger" icon="fa fa-minus" @click="onClickDel(i)"></el-button>
        </td>
        <td v-for="(b,j) in structure" :key="j">
          <el-input
            v-model="d[b.name]"
            v-if="b.type=='text'"
            v-bind="b.attributes"
            autosize
            type="textarea"
            show-word-limit
          ></el-input>

          <input
            v-if="b.type=='image'"
            v-model="d[b.name]"
            is="fileman"
            :name="`s.name_${i}`"
            :data-field="`s.name_${i}`"
            url="Fileman/?token="
          />

          <div v-if="b.type=='list'">
            <dynamic-page-table :structure="b.body" :data.sync="data[b.name]"></dynamic-page-table>
          </div>
        </td>
      </tr>
    </tbody>
  </table>
</template>
<script>
module.exports = {
  props: {
    data: Array,
    structure: Array,
  },
  created() {
    console.log("data", this.data);
    for (b of this.structure) {
      if (b.type == "list") {
        this.data[b.name] = this.data[b.name] ?? [];
        console.log(this.data);
      }
    }
  },
  methods: {
    onClickDel(i) {
      this.data.splice(i, 1);
      this.$forceUpdate();
    },
    onClickAdd() {
      console.log("click add");
      var d = {};
      for (b of this.structure) {
        if (b.type == "list") {
          d[b.name] = [];
        } else {
          d[b.name] = "";
        }
      }
      this.data.push(d);
      this.$forceUpdate();
    },
  },
};
</script>