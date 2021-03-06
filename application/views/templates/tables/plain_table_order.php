<div class="table-responsive">
    <table id="table" class="table table-striped table-bordered table-hover  ">
        <thead>
            <tr>
                <th style="width:50px">No</th>
                <?php foreach ($header as $key => $value) : ?>
                    <th><?php echo $value ?></th>
                <?php endforeach; ?>
                <?php if (isset($action)) : ?>
                    <th><?php echo "Aksi" ?></th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = (isset($number) && ($number != NULL))  ? $number : 1;
            foreach ($rows as $ind => $row) :
                ?>
                <tr>
                    <input type='hidden' name='id_<?= $no ?>' id='id_<?= $no ?>' value='<?= $row->id ?>'>
                    <input type='hidden' name='product_id_<?= $no ?>' id='product_id_<?= $no ?>' value='<?= $row->product_id ?>'>
                    <?php if ($row->varian_id) : ?>
                        <input type='hidden' name='varian_id_<?= $no ?>' id='varian_id_<?= $no ?>' value='<?= $row->varian_id ?>'>
                    <?php endif; ?>
                    <input type='hidden' name='price_<?= $no ?>' id='price_<?= $no ?>' value='<?= $row->product_price ?>'>
                    <td data-title='No'> <?php echo $no++ ?> </td>
                    <?php foreach ($header as $key => $value) : ?>
                        <td data-title=' <?= $value ?>'>
                            <?php
                                    $attr = "";
                                    if (is_numeric($row->$key) && ($key != 'phone' && $key != 'username'))
                                        $attr = number_format($row->$key);
                                    else
                                        $attr = $row->$key;
                                    if ($key == 'date' || $key == 'create_date' || $key == 'time')
                                        $attr =  date("d-m-Y", $row->$key);
                                    if ($key == 'quantity' || $key == 'qty')
                                        $attr = "<input type='text' name='" . $key . "_" . ($no - 1) . "' id='" . $key . "_" . ($no - 1) . "' value='" . $row->$key . "' class='form-control'>";
                                    echo $attr;
                                    ?>
                        </td>
                    <?php endforeach; ?>
                    <?php if (isset($action)) : ?>
                        <td>

                            <!--  -->
                            <!-- <div class="btn-group"> -->
                            <!-- <ul class="nav navbar-nav"> -->
                            <?php
                                    foreach ($action as $ind => $value) :
                                        ?>
                                <!-- <li>                                 -->
                                <?php
                                            switch ($value['type']) {
                                                case "link":
                                                    $value["data"] = $row;
                                                    $this->load->view('templates/actions/link', $value);
                                                    break;
                                                case "download":
                                                    $value["data"] = $row;
                                                    $this->load->view('templates/actions/download', $value);
                                                    break;
                                                case "modal_delete":
                                                    $value["data"] = $row;
                                                    $this->load->view('templates/actions/modal_delete', $value);
                                                    break;
                                                case "modal_form":
                                                    $value["data"] = $row;
                                                    $this->load->view('templates/actions/modal_form', $value);
                                                    break;
                                                case "modal_form_multipart":
                                                    $value["data"] = $row;
                                                    $this->load->view('templates/actions/modal_form_multipart', $value);
                                                    break;
                                                case "button_dropdowns":
                                                    $value["data"] = $row;
                                                    $this->load->view('templates/actions/button_dropdown', $value);
                                                    break;
                                            }
                                            ?>
                                    <!-- </li> -->
                                <?php
                                        endforeach;
                                        ?>
                                <!-- </ul> -->
                                <!-- </div> -->
                                <!--  -->
                        </td>
                    <?php endif; ?>
                </tr>
            <?php
            endforeach;
            ?>
            <input type="hidden" name="total_order" value="<?= $no ?>">
        </tbody>
    </table>
</div>
<script>
    var width = window.innerWidth;
    console.log(width);
    var element = document.getElementById('table');

    if (width <= 600) {
        element.classList.add('rg-table');
    } else {
        element.classList.remove('rg-table');
    }
</script>