/**
 * Created by raven on 2017/5/23.
 */
var temp_data = '';
var clean = {

    clean_by_id : function (tag, type) {
        $.each(tag, function (i, v) {
            if (type == 1) {
                $('#' + v).val('');
            }
            if (type == 2) {
                $('#' + v).html('');
            }
        })
    },

    reset_form : function (dom, btn) {
        $(dom).click();
        $('#folder_module_id,#controller_module_id,#function_module_id').val($('#module_id').val());
        $('#controller_folder_id').val($('#folder_id').val());
        $('#function_controller_id').val($('#controller_id').val());
        $('#save_btn').val(btn);
    }
};

var fill = {
    fill_by_id : function (tag, data, type) {
        var k;
        $.each(tag, function (i, v) {
            '' == v ? k = i : k = v;
            if (type == 1) {
                $('#' + i).val(data[k]);
            } else if (type == 2) {
                $('#' + i).html(data[k]);
            }
        })
    }
};

/**
 * 提交表单
 * @type {{submit: submit.submit}}
 */

var submit = {
    submit : function (url, data, id) {
        if (!id) id = 'submitForm';
        if (!data) {
            data = $('#' + id).serialize();
        }
        $.post(url, data, function (res) {
            message.message(res);
        }, "JSON");
    }
};

/**
 * 获取数据
 * @type {{message: message.message}}
 */
var getInfo = {

    getToken : function (url, set, type) {
        var data = $('form').serialize();
        $.post(url, data, function (res) {
            if (res.code == 200) {
                return fill.fill_by_id(set, res.info, type);
            } else {
                message.message({info : res.info, code : res.code});
            }
        }, "JSON");
    },

    getInfo : function (url, data, set) {
        $.post(url, data, function (res) {
            if (res.code == 200) {
                $.each(set, function (i, v) {
                    fill.fill_by_id(v.set, res, v.type);
                });
                return;
            } else {
                message.message({info : res.info, code : res.code});
            }
        }, "JSON");
    },

    getSelect : function (url, where, id, value) {
        var option;
        $.post(url, where, function (res) {
            $.each(res, function (i, v) {
                option = '<option value="' + v.id + '">' + v[value] + '</option>';
                $('#' + id).append(option);
            });
        }, "JSON")
    },

    getList : function (url) {
        $.ajax({
            url     : url,
            type    : 'POST',
            async   : false,
            success : function (res) {
                temp_data = res.list;
            }
        });
        return temp_data;
    }
};

var check = {

    Auth : function (id, data) {
        var group_url = '/AuthGroup/Edit';
        var rules_url = '/AuthRule/edit_';
        $.post(group_url, {id : id}, function (res) {
            var rules = res.rules.split(',');
            $.each(data, function (i, v) {
                $.post(rules_url, {id : v, name : 'name'}, function (result) {
                    if ($.inArray(result.id, rules) == -1) {
                        $('#' + i).hide();
                    }
                }, "JSON");
            });
        }, "JSON");
    }
};

var message = {
    message : function (info) {
        console.log(info);
        if (info.code == 200) {
            return swal({
                title             : info.message,
                timer             : 1500,
                type              : 'success',
                showConfirmButton : false
            }, function () {
                if (info.url) {
                    window.location.href = info.url;
                } else {
                    swal.close();
                }
            })
        } else if (info.code == 400) {
            return swal({
                title             : info.message,
                timer             : 1500,
                type              : 'error',
                showConfirmButton : false
            }, function () {
                if (info.url) {
                    window.location.href = info.url;
                } else {
                    swal.close();
                }
            })
        } else if (info.code == 401) {
            return swal({
                title             : '您没有权限进行该操作',
                timer             : 1500,
                type              : 'error',
                showConfirmButton : false
            }, function () {
                if (info.url) {
                    window.location.href = info.url;
                } else {
                    swal.close();
                }
            })
        } else {
            return swal(info.message, '', 'error');
        }
    },

    //未开发的功能
    undo : function () {
        message.message({info : 'Coding', status : 0});
    }
};