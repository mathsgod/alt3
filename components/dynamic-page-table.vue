<template>
  <div>
    <div class="mb-3">
      <el-button size="mini" type="primary" icon="fa fa-plus" @click="onClickAdd"></el-button>
    </div>
    <div
      class="d-flex flex-wrap mb-3"
      v-for="(d,i) in data"
      :key="i"
      style="background-color: #f3f3f3;padding: 15px;"
    >
      <div class="mr-5">
        <el-button size="mini" type="danger" icon="fa fa-minus" @click="onClickDel(i)"></el-button>
      </div>
      <div class="flex-fill">
        <div class="row">
          <div
            class="col-12 mb-3"
            v-for="(b,j) in structure"
            :key="j"
            v-bind:class="{'col-lg-6 col-xl-4': b.type!='list'}"
          >
            <div v-text="structure[j].name"></div>
            <el-input
              v-model="d[b.name]"
              v-if="b.type=='text'"
              v-bind="b.attributes"
              :autosize="{ minRows: 2}"
              type="textarea"
              show-word-limit
              @input="$forceUpdate()"
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
              <dynamic-page-table
                ref="dpt"
                :structure="b.body"
                :data="d[b.name]"
                @add="d[b.name].push($event)"
              ></dynamic-page-table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
module.exports = {
  props: {
    data: Array,
    structure: Array,
  },
  watch: {},
  created() {},
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
      this.$emit("add", d);
      this.$forceUpdate();
    },
  },
};
</script>