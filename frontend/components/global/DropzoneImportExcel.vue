<template>
  <vue-dropzone ref="myFile" id="register_file" :options="dropzoneOptions" v-on:vdropzone-sending="previewFile"
    :useCustomSlot=true>
    <div class="dropzone-custom-content">
      <h3>Upload or drop your file here</h3>
      <div class="text-muted">Upload an .xlsx file</div>
      <br>
      <button class="btn btn-default"><i class='fa fa-cloud-upload' /> Upload File</button>
    </div>
  </vue-dropzone>
</template>

<script>
  import vue2Dropzone from 'vue2-dropzone'
  import 'vue2-dropzone/dist/vue2Dropzone.min.css'
  export default {
    name: 'DropzoneImportExcel',
    props: {
      previewFile: Function,
      url: {
        type: String,
        default: 'https://httpbin.org/post'
      },
      thumbnailWidth: {
        type: Number,
        default: 200
      },
      addRemoveLinks: {
        type: Boolean,
        default: true
      },
      acceptedFiles: {
        type: String,
        default: '.xlsx,.xls"'
      },
      maxFiles: {
        type: String,
        default: '1'
      },
      maxFilesize: {
        type: Number,
        default: 10
      }
    },
    components: {
      vueDropzone: vue2Dropzone,
    },
    data() {
      return {
        isLoading: false,
        dropzoneOptions: {
          url: this.url,
          thumbnailWidth: this.thumbnailWidth,
          addRemoveLinks: this.addRemoveLinks,
          acceptedFiles: this.acceptedFiles,
          maxFiles: this.maxFiles,
          maxFilesize: this.maxFilesize
        }
      }
    },
    created() {
      this.$bus.$on('reset-importfile', this.doResetFile)
    },
    methods: {
      doResetFile () {
        const file = document.querySelectorAll('#register_file');
        if (file.length) {
            for (let index = 0; index < file.length; index++) {
              let element = file[index];
              if (element.dropzone.files.length) {
                let buttonReset = element.dropzone.files[0]._removeLink;
                buttonReset.click()
              }
            }
        }
      }
    }
  }
</script>