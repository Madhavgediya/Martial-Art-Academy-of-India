<?php
class book_issue_history {

    public $table = 'sm_book_issue_history';
    public $action = '';
    public $parameters = '';
    public $cquery = '';
    public $data = array();
    public $where = '';
    public $order = '';
    public $validate = '';
    public $process_id = 0;

    // ********************** SAVE FUNCTION *******************************
    function process() {
        $condition = '';
        if ($this->validate != '') {
            // CODE FOR VALIDATE DETAILS
        }
        if ($this->action == 'update' || $this->action == 'delete' || $this->action == 'get') {
            $condition = $this->where != '' ? $this->where : "";
            if ($condition == '' && $this->process_id != 0)
                $condition = "bi_id=" . $this->process_id;

            if ($condition == '' && ($this->action == 'update' || $this->action == 'delete' )) {
                return array("errormsg" => "Server Communication Error: 022", "id" => 0, "status" => "failure");
            }
        }

        return db_perform($this->table, $this->data, $this->action, $condition, $this->order, $this->cquery);
    }

}

?>