$(function(){
  $('#dg1').datagrid({
     url : 'api/index.php',
     queryParams :{
      getData : 'barang',
     },
     pageSize : 5,
     pageList: [5, 10, 15, 20, 25]
  })
})

function add (){
   let $dg1 = $('#dg1'),
   $dg2 = $('#dg2'),
   rowsSelections = $dg1.datagrid('getSelections'),
   queryParams = $dg1.datagrid('options').queryParams,
   tempCode = [];

   //console.log(rowsSelections);
  $.each(rowsSelections, (i, r) => {
    let indx = $dg1.datagrid('getRowIndex', r )
    $dg1.datagrid('deleteRow', indx);

    // insert to grid right
    $dg2.datagrid('insertRow', {
      index: 1,
      row: r
    })
  })

  $.each($dg2.datagrid('getRows'), (i, r) => {
    tempCode.push(r.code)
   })

  $('#temp-code').val(tempCode.join(','))

  // refresh grid
  queryParams['code'] = $('#temp-code').val();
  $dg1.datagrid('load', queryParams)
}

function remove(){
  let $dg1 = $('#dg1'),
   $dg2 = $('#dg2'),
   rowSelected = $dg2.datagrid('getSelected'),
   queryParams = $dg1.datagrid('options').queryParams,
   indx = $dg2.datagrid('getRowIndex', rowSelected),
   tempCode = [];
  
   // remove Right
   $dg2.datagrid('deleteRow', indx);
   // append Left
   $dg1.datagrid('appendRow', rowSelected);

   $.each($dg2.datagrid('getRows'), (i, r) => {
    tempCode.push(r.code)
   })

   $('#temp-code').val(tempCode.join(','))
   // refresh grid
  queryParams['code'] = $('#temp-code').val();
  $dg1.datagrid('load', queryParams)
}