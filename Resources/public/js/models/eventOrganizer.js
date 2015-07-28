define(['mvc/relationalmodel'], function(RelationalModel) {
    return RelationalModel({
        urlRoot: '',
        defaults: {
            id: null,
            title: '',
            firstName: '',
            lastName: '',
            street: '',
            zip: '',
            city: '',
            phone: '',
            fax: '',
            email: ''
        }
    });
});
