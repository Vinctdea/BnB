import dropin from 'braintree-web-drop-in';

export default {
  install(app) {
    app.config.globalProperties.$braintree = {
      createDropin: (options) => {
        return dropin.create(options);
      },
    };
  },
};
