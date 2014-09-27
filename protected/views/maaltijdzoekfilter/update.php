
<h1>Maaltijdzoekfilter</h1>
<br>

<form class="form-horizontal" method="post" role="form">
    <input type="hidden" name="Maaltijdzoekfilter[id]" value="<?=$zoekfilter->id?>">
    <div class="form-group">
        <label for="maaltijdtype_id" class="col-md-2 control-label">Maaltijdtype</label>
        <div class="col-md-4">
            <select class="form-control" name="Maaltijdzoekfilter[maaltijdtype_id]">
                <?php 
                    foreach($maaltijdtypeDescriptions as $id=>$description) {
                        $options = array('value'=>$id);
                        if ($zoekfilter->maaltijdtype_id == $id ) {
                            $options['selected'] = true;
                        }
                        echo CHtml::tag('option', $options, $description);
                    }
                ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="maaltijdsubtype_id" class="col-md-2 control-label">Maaltijdsubtype</label>
        <div class="col-md-4">
            <select class="form-control" name="Maaltijdzoekfilter[maaltijdsubtype_id]">
                <?php
                    foreach($maaltijdsubtypeDescriptions as $id=>$description) {
                        $options = array('value'=>$id);
                        if ($zoekfilter->maaltijdsubtype_id == $id ) {
                            $options['selected'] = true;
                        }
                        echo CHtml::tag('option', $options, $description);
                    }
                ?>
            </select>
        </div>
    </div>
    <div class="form-group">
      <label for="tooltip" class="col-md-2 control-label">Tooltip</label>
      <div class="col-md-6">
        <textarea class="form-control" id="tooltip" rows="5" name="Maaltijdzoekfilter[tooltip]"><?=$zoekfilter->tooltip?></textarea>
      </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
          <button type="submit" class="btn btn-default">Opslaan</button>
        </div>
    </div>
</form>
