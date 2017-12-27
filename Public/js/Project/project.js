/**
 * Created by raven on 2017/12/25.
 */
document.onkeydown = function (e) {
    var e = window.event ? window.event : e;
    if (e.keyCode == 13) {
        save();
    }
};

$(function () {
    init();
    link_function_init();
});

function init() {
    var project_id = $('#project_id').val();
    var project = '';
    var html = '';
    var type = '';
    var option = '';
    var code_editor = CodeMirror.fromTextArea(document.getElementById("function_code"), {
        lineNumbers     : true,
        matchBrackets   : true,
        styleActiveLine : true,
        theme           : "ambiance"
    });
    $.ajax({
        url     : config.base_url + '/Project/edit',
        type    : 'POST',
        data    : {id : project_id},
        async   : false,
        success : function (res) {
            project = res.info;
            fill.fill_by_id({project_name : 'name'}, project, 2);
            fill.fill_by_id({module_project_id : 'id', controller_project_id : 'id', function_project_id : 'id'}, project, 1);
        }
    });

    var module = getInfo.getList(config.base_url + '/Module/getList');
    var folder = getInfo.getList(config.base_url + '/Folder/getList');
    var controller = getInfo.getList(config.base_url + '/Controller/getList');
    var function_ = getInfo.getList(config.base_url + '/Function/getList');

    var types = {
        'default'         : {'icon' : 'fa fa-list'},
        'Add'             : {'icon' : 'fa fa-plus'},
        'Edit'            : {'icon' : 'fa fa-pencil'},
        'Del'             : {'icon' : 'fa fa-close'},
        'Profile'         : {'icon' : 'fa fa-github'},
        'GetToken'        : {'icon' : 'fa fa-key'},
        'Change_Head_img' : {'icon' : 'fa fa-recycle'},
        'module'          : {'icon' : 'fa fa-folder'},
        'folder'          : {'icon' : 'fa fa-folder-o'},
        'controller'      : {'icon' : 'fa fa-copyright'},
        'function_'       : {'icon' : 'fa fa-terminal'}
    };
    option = '';
    //加载模块
    $.each(module, function (i, v) {
        type = '{"type":"module"}';
        html = "<li data-jstree='" + type + "')'><a href='#' onclick='show_edit(" + v.id + ",1)'><span id='Module_name_" + v.id + "'>" + v.name + "</span></a><ul id='module_" + v.id + "'></ul></li>";
        option += '<option value="' + v.id + '">' + v.name + '</option>';
        $("#app").append(html);
    });
    $('#folder_module_id,#controller_module_id,#function_module_id').append(option);


    //加载菜单
    option = '';
    $.each(folder, function (i, v) {
        type = '{"type":"folder"}';
        html = "<li data-jstree='" + type + "'><button type='button' style='border: none;padding: 0' class='btn-link' onclick='show_edit(" + v.id + ",2)'><span id='Folder_name_" + v.id + "'>" + v.name + "</span></button><ul id='folder_" + v.id + "'></ul></li>";
        $('#module_' + v.module_id).append(html);
        option += '<option value="' + v.id + '">' + v.name + '</option>';
    });
    $('#controller_folder_id').append(option);

    //加载控制器
    option = '';
    $.each(controller, function (i, v) {
        type = '{"type":"controller"}';
        html = "<li data-jstree='" + type + "'><button type='button' style='border: none;padding: 0' class='btn-link' onclick='show_edit(" + v.id + ",3)'><span id='Controller_name_" + v.id + "'>" + v.name + "</span></button><ul id='controller_" + v.id + "'></ul></li>";
        option += '<option value="' + v.id + '">' + v.name + '</option>';
        $('#folder_' + v.folder_id).append(html);
    });
    $('#function_controller_id').append(option);

    //加载方法
//            option = '';
    $.each(function_, function (i, v) {
        type = '{"type":"function_"}';
        html = "<li data-jstree='" + type + "'><button style='border: none;padding: 0' class='btn-link' onclick='show_edit(" + v.id + ",4)'><span id='Function_name_" + v.id + "'>" + v.type_name + " function <span style='color:#1ab394'>" + v.name + "</span></span></button><ul id='controller_" + v.id + "'></ul></li>";
//                option += '<option value="' + v.id + '">' + v.name + '</option>';
        $('#controller_' + v.controller_id).append(html);
    });
//            $('#function_controller_id').append(option);

    $('#tree').jstree({
        'core'    : {
            'check_callback' : true
        },
        'plugins' : ['types', 'dnd'],
        'types'   : types
    });
}
/**
 * 方法关联模态框初始化
 */
function link_function_init() {
    var url = config.base_url + '/Function/getFunctions';
    var html = '';
    $.post(url, {}, function (res) {
        var controller = res.list;
        $.each(controller, function (i, v) {
            html += '<p>' + v.name + '</p>' +
                '<p style="border-bottom: 1px solid #ccc"></p>';
            if (v.functions) {
                html += '<p>';
                $.each(v.functions, function (k, vo) {
                    html += '<button type="button" class="btn btn-xs btn-default used-function-btn" style="margin-right: 5px" onclick="choose_link(' + vo.id + ')" id="used_function_' + vo.id + '">' + vo.type_name + ' function <span style="color:#f8ac59">' + vo.name + '</span></button>';
                });
                html += '</p>';
            }
        });
        $('#functions_area').append(html);
    }, "JSON");
}

function show_edit(id, type) {
    var name = '';
    var icon = '';
    var area = '';
    if (type == 1) {
        icon = 'fa fa-folder';
        area = 'Module';
        name = $('#' + area + '_name_' + id).html();
    } else if (type == 2) {
        icon = 'fa fa-folder-o';
        area = 'Folder';
        name = $('#' + area + '_name_' + id).html();
    } else if (type == 3) {
        icon = 'fa fa-copyright';
        area = 'Controller';
        name = $('#' + area + '_name_' + id).html();
    } else if (type == 4) {
        icon = 'fa fa-terminal';
        area = 'Function';
        name = $('#' + area + '_name_' + id).html();
        fill_function_detail(id);
    }
    var html = '<i class="' + icon + '">&nbsp;</i>' + name;
    $('#edit_id').val(id);
    $('#edit_modal').val(area);
    $('#edit_area').html(html);
    $('#used_function_name,#code_function_name').html(html);
    $('.CodeMirror').remove();
    code_editor = CodeMirror.fromTextArea(document.getElementById("function_code"), {
        lineNumbers     : true,
        matchBrackets   : true,
        styleActiveLine : true,
        autofocus       : true,
        theme           : "ambiance"
    });
}

//编辑路由
function edit() {
    var id = $("#edit_id").val();
    if (!id) return message.message({code : 400, message : 'ID can not be Empty !'});
    var modal = $('#edit_modal').val();
    if (!modal) return message.message({code : 400, message : 'Modal can not be Empty !'});
    var url = config.base_url + '/' + modal + '/edit';
    modal = modal.toLowerCase();
    var data = '';
    var tag = '';
    var type_id = {
        1 : 'function_type_public',
        2 : 'function_type_protected',
        3 : 'function_type_private'
    };
    var save_btn = $('#save_btn');
    if (modal == 'module') {
        tag = {
            module_id         : 'id',
            module_name       : 'name',
            module_list_order : 'list_order'
        };
        save_btn.val('module_save');
    } else if (modal == 'folder') {
        tag = {
            folder_id         : 'id',
            folder_name       : 'name',
            folder_module_id  : 'module_id',
            folder_list_order : 'list_order'
        };
        save_btn.val('folder_save');
    } else if (modal == 'controller') {
        tag = {
            controller_id          : 'id',
            controller_name        : 'name',
            controller_module_id   : 'module_id',
            controller_folder_id   : 'folder_id',
            controller_description : 'description',
            controller_list_order  : 'list_order'
        };
        save_btn.val('controller_save');
    } else if (modal == 'function') {
        tag = {
            function_id            : 'id',
            function_name          : 'name',
            function_module_id     : 'module_id',
            function_controller_id : 'controller_id',
            function_type          : 'type',
            function_description   : 'description',
            function_list_order    : 'list_order'
        };
        save_btn.val('function_save');
    }
    $.post(url, {id : id}, function (res) {
        data = res.info;
        fill.fill_by_id(tag, data, 1);
        if (modal == 'function') {
            chooseFunctionType(data.type, type_id[data.type]);
        }
        $('#add_' + modal).modal('toggle');
    }, "JSON");
}

function fill_function_detail(id) {
    var url = config.base_url + '/Function/edit';
    $.ajax({
        url     : url,
        type    : 'POST',
        data    : {id : id},
        async   : false,
        success : function (res) {
            //右侧Detail内容填充
            $(".rule-box").removeClass('form-group');
            var tag = {path : 'path', description : 'description', used_path : 'used_path', function_path : 'function_path', javascript_path : 'javascript_path', function_code : 'content'};
            fill.fill_by_id(tag, res.info, 2);
            //关系表填充
            tag = {function_used : 'used', function_used_old : 'used'};
            fill.fill_by_id(tag, res.info, 1);
        }
    });
}

function choose_link(id) {
    update_used(id);
    id = 'used_function_' + id;
    changeClass(id, 'btn-default', 'btn-info');
}

/**
 * 去除元素的方法
 */
function remove_used(arr, val) {
    for (var i = 0; i < arr.length; i++) {
        arr[i] == val && arr.splice(i, 1);
    }
}

function update_used(id) {
    var used = $('#function_used');
    var used_list = used.val();
    if (used_list) {
        used_list = used_list.split(',');
        var is_new = true;
        $.each(used_list, function (i, v) {
            if (id == v) {
                is_new = false;
            }
        });
        if (is_new) {
            used_list.push(id);
        } else {
            remove_used(used_list, id);
        }
        used_list = used_list.join(',');
    } else {
        used_list = id;
    }
    used.val(used_list);

}

function show_link() {
    var modal = $('#edit_modal').val();
    if (modal != 'Function') return message.message({code : 400, message : 'Please choose function !'});
    $('.used-function-btn').removeClass().addClass('btn btn-xs btn-default');
    var used = $('#function_used').val();
    var used_list = used.split(',');
    if (used) {
        $.each(used_list, function (i, v) {
            changeClass('used_function_' + v, 'btn-info', 'btn-default');
        });
    }
    $('#save_btn').val('used_save');
    $('#add_link').modal('toggle');
}

function save() {
    var save_btn = $('#save_btn').val();
    $('#' + save_btn).click();
}

function save_module() {
    var name = $('#module_name').val();
    if (!name) return message.message({code : 400, message : 'Module Name Can not be none !'});

    var data = $("#module_form").serialize();
    var url = config.base_url + '/Module/Add';
    $('#add_module').modal('toggle');
    submit.submit(url, data);
}

function save_folder() {
    var name = $('#folder_name').val();
    if (!name) return message.message({code : 400, message : 'Folder Name Can not be none !'});
    var module_id = $('#folder_module_id').val();
    if (!module_id) return message.message({code : 400, message : 'Please choose module !'});

    var data = $("#folder_form").serialize();
    var url = config.base_url + '/Folder/Add';
    $('#add_folder').modal('toggle');
    submit.submit(url, data);
}

function save_controller() {
    var name = $('#controller_name').val();
    if (!name) return message.message({code : 400, message : 'Controller Name Can not be none !'});
    var module_id = $('#controller_module_id').val();
    if (!module_id) return message.message({code : 400, message : 'Please choose module !'});

    var data = $("#controller_form").serialize();
    var url = config.base_url + '/Controller/Add';
    $('#add_controller').modal('toggle');
    submit.submit(url, data);
}

function chooseFunctionType(type, id) {
    $('#function_type').val(type);
    var ids = {
        0 : 'function_type_public',
        1 : 'function_type_protected',
        2 : 'function_type_private'
    };
    $.each(ids, function (i, v) {
        $('#' + v).removeClass().addClass('btn btn-default');
    });
    changeClass(id, 'btn-info', 'btn-default');
}

/**
 * 更改样式的方法
 */
function changeClass(id, origin, replace) {
    var bool;
    var target = $('#' + id);
    var cls = target.attr('class');
    bool = cls.indexOf(origin) + 1;

    0 != bool && target.removeClass(origin).addClass(replace);
    0 == bool && target.removeClass(replace).addClass(origin);
}

function save_function() {
    var name = $('#function_name').val();
    if (!name) return message.message({code : 400, message : 'Function Name Can not be none !'});
    var module_id = $('#function_module_id').val();
    if (!module_id) return message.message({code : 400, message : 'Please choose module !'});
    var controller_id = $('#function_controller_id').val();
    if (!controller_id) return message.message({code : 400, message : 'Please choose controller !'});

    var data = $("#function_form").serialize();
    var url = config.base_url + '/Function/Add';
    $('#add_function').modal('toggle');
    submit.submit(url, data);
}

function save_used() {
    var id = $('#edit_id').val();
    var modal = $('#edit_modal').val();
    if (modal != 'Function') return message.message({code : 400, message : 'Something Wrong ! Refresh The Page And Retry !'});
    var url = config.base_url + '/Function/add';
    var used = $('#function_used').val();
    var used_old = $('#function_used_old').val();
    var skipping_link = $('#skipping_link').val();
    var data = {id : id, used : used, used_old : used_old, skipping_link : skipping_link};
    submit.submit(url, data);
}

function save_code() {
    var id = $('#edit_id').val();
    var modal = $('#edit_modal').val();
    if (modal != 'Function') return message.message({code : 400, message : 'Something Wrong ! Refresh The Page And Retry !'});
    var url = config.base_url + '/Function/add';
    var content = code_editor.getValue();
    var skipping_link = $('#skipping_link').val();
    var data = {id : id, content : content, skipping_link : skipping_link};
    submit.submit(url, data);
}