$(function(){
  let moduleCount = 0;

  function buildModule(){
    const $tpl = $($('#module-template').html());
    const moduleIndex = moduleCount++;
    $tpl.find('.module-index').text(moduleIndex+1);
    // set input names
    const moduleName = `modules[${moduleIndex}]`;
    $tpl.find('.module-title').attr('name', `${moduleName}[title]`);
    $tpl.find('.module-desc').attr('name', `${moduleName}[description]`);

    // contents wrapper
    const $contentsWrapper = $tpl.find('.contents-wrapper');
    let contentCount = 0;

    $tpl.find('.add-content').on('click', function(){
      const $ctpl = $($('#content-template').html());
      const contentIndex = contentCount++;
      $ctpl.find('.content-index').text(contentIndex+1);
      const contentName = `${moduleName}[contents][${contentIndex}]`;
      $ctpl.find('.content-title').attr('name', `${contentName}[title]`);
      $ctpl.find('.content-type').attr('name', `${contentName}[type]`);
      $ctpl.find('.field-body textarea').attr('name', `${contentName}[body]`);
      $ctpl.find('input[type=file]').attr('name', `${contentName}[file]`);

      // toggle fields based on type
      $ctpl.find('.content-type').on('change', function(){
        const t = $(this).val();
        if(t === 'text' || t === 'link'){
          $ctpl.find('.field-body').show();
          $ctpl.find('.field-file').hide();
        }else{
          $ctpl.find('.field-body').hide();
          $ctpl.find('.field-file').show();
        }
      }).trigger('change');

      $ctpl.find('.remove-content').on('click', function(){ $ctpl.remove(); });
      $contentsWrapper.append($ctpl);
    });

    $tpl.find('.remove-module').on('click', function(){ $tpl.remove(); });

    return $tpl;
  }

  $('#add-module').on('click', function(){
    $('#modules-wrapper').append(buildModule());
  });

  // add one module by default
  $('#add-module').trigger('click');
});
