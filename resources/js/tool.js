import MediableManager from './components/Tool'
import FileUpload from 'vue-upload-component'
import IndexMediable from './field/IndexField'
import DetailMediable from './field/DetailField'
import FormMediable from './field/FormField'
import IconMUpload from './icons/Upload'
import IconMDocument from './icons/Document'
Nova.booting((Vue, router, store) => {

    Vue.component('file-upload', FileUpload);
    Vue.component('index-mediable', IndexMediable);
    Vue.component('detail-mediable', DetailMediable);
    Vue.component('form-mediable', FormMediable);
    Vue.component('icon-m-upload', IconMUpload);
    Vue.component('icon-m-document', IconMDocument);

  router.addRoutes([
    {
      name: 'nova-mediable-manager',
      path: '/nova-mediable-manager',
      component: MediableManager,
    },
  ])
})
