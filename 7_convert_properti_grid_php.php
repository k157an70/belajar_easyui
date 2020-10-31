<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Basic jQuery EasyUI</title>
    <link rel="stylesheet" type="text/css" href="themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="themes/icon.css">
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/jquery.easyui.min.js"></script>
</head>
<?php
    $optGrid = [];
    $columns = [[
        ['field'=>'code','title'=>'Code','width'=>100],
        ['field'=>'name','title'=>'Name','width'=>100],
        ['field'=>'price','title'=>'Price','width'=>100,'align'=>'right']
    ]];
   // die( str_replace('"', "'",  preg_replace('/"([^"]+)"\s*:\s*/', '$1:', json_encode($columns)) ) );
    $setGrid = [
        'title'=>"'DATA BARANG'",
        'toolbar'=> "'#toolbar'",
        'url'=> "'api/index.php'",
        'queryParams'=> "{ getData: 'barang' }",
        'pageSize'=> '5',
        'pageList'=> '[5, 10, 15, 20, 25]',
        'pagination'=>'true',
        'rownumbers'=>'true',
        'fitColumns'=>'true',
        'fit'=>'true',
        'singleSelect'=>'true',
        'columns'=> str_replace('"', "'",  preg_replace('/"([^"]+)"\s*:\s*/', '$1:', json_encode($columns)) ),
        'onLoadSuccess'=> "app.grid.onLoadSuccess"
    ];

    foreach($setGrid as $key => $val)
        $optGrid[] = "$key:$val";
?>
<body>
    <table class="easyui-datagrid" id="dg" data-options="<?php echo join(",", $optGrid) ?>"></table>
    <!-- TOOLBAR -->
    <div id="toolbar">
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newBarang()">New</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editBarang()">Edit</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyBarang()">Remove</a>
        <div style="float:right">
            <input class="easyui-searchbox" data-options="prompt:'Search Code or Name',searcher:doSearch" style="width:100%"/>
        </div> 
    </div>
    <!-- END TOOLBAR -->
    <!-- DIALOG -->
    <div id="dlg" class="easyui-dialog" style="width:400px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'">
        <form id="fm" method="post" style="margin:0;padding:20px 50px">
            <input name="code" type="hidden" />
            <div style="margin-bottom:10px">
                <input name="name" class="easyui-textbox" required="true" label="Name:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="price" class="easyui-numberbox" required="true" label="Price:" style="width:100%">
            </div>
        </form>
    </div>
    <div id="dlg-buttons">
        <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveBarang()" style="width:90px">Save</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
    </div>
    <!-- END DIALOG -->
    <script>
        // app.grid.onLoadSuccess
        var app = {
            grid: {
                onLoadSuccess: function(data){
                    console.log(JSON.stringify(data, null, 2))
                }
            }
        }

        function newBarang() {
            $('#dlg').dialog('open').dialog('center').dialog('setTitle', 'New Barang');
            $('#fm').form('clear');
            // url = 'save_user.php';
        }
        function editBarang() {
            var row = $('#dg').datagrid('getSelected');
            if (row) {
                $('#dlg').dialog('open').dialog('center').dialog('setTitle', 'Edit Barang');
                $('#fm').form('load', row);
                //url = 'update_user.php?id='+row.id;
            }
        }
        function saveBarang() {
            $('#fm').form('submit', {
                url: 'api/crud_barang.php',
                onSubmit: function () {
                    return $(this).form('validate');
                },
                success: function (result) {
                    // alert(result);return;// string
                    result = JSON.parse(result);// eval('(' + result + ')');
                    if (result.isSuccess) {
                        $('#dlg').dialog('close');        // close the dialog
                        $('#dg').datagrid('reload');    // reload the user data
                        $.messager.show({
                            title: 'Information',
                            msg: result.isMessage,
                            showType: 'slide'
                        });
                    } else { // gagal
                        $.messager.alert('Error', result.isMessage, 'error')
                    }
                }
            });
        }
        function destroyBarang(){
            var row = $('#dg').datagrid('getSelected');
            if(!row){
                $.messager.alert('Error', 'Silahkan pilih barang yang akan dihapus', 'error');
                return;
            }

            $.messager.confirm('Confirm','Are you sure you want to destroy "<b>'+ row.name +'</b>" ?',function(r){
                if(!r) return;
                $.post('api/crud_barang.php',{code:row.code, action: 'delete' },function(result){
                    if (result.isSuccess){
                        $('#dg').datagrid('reload');    // reload the user data
                        $.messager.show({
                            title: 'Information',
                            msg: result.isMessage,
                            showType: 'slide'
                        });
                    } else {
                        $.messager.alert('Error', result.isMessage, 'error')
                    }
                },'json');
            });
        }
        function doSearch(val){
            var $dg = $('#dg'),
            prevQueryParams = $dg.datagrid('options')['queryParams'],
            newQueryParams = $.extend(prevQueryParams, { cari: val} );

            $dg.datagrid('load', newQueryParams);

        }
    </script>
</body>

</html>