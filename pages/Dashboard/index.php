<?

class Dashboard_index extends ALT\Page
{
  public function get()
  {

    $card = $this->createCard();
    $card->body()->append("test");
    $this->write($card);

    return;

    $this->write(
      <<<html
        <div class="card">
        <div class="card-header">
          <h3 class="card-title">Default Card Example</h3>
          <div class="card-tools">
            <!-- Buttons, labels, and many other things can be placed here! -->
            <!-- Here is a label for example -->
            <span class="badge badge-primary">Label</span>
          </div>
          <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          The body of the card
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          The footer of the card
        </div>
        <!-- /.card-footer -->
      </div>
      <!-- /.card -->    
html
    );
  }
}
