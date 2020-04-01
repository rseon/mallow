<?php
if(isset($this->layout_breadcrumbs) && $this->layout_breadcrumbs) {
    ?>
    <ol class="breadcrumb">
        <?php
        $last = count($this->layout_breadcrumbs)-1;
        foreach($this->layout_breadcrumbs as $i => $b) {
            $text = $b['text'] ?? null;
            if(isset($b['icon']) && $b['icon']) {
                $text = '<i class="fa fa-fw fa-'.$b['icon'].'"></i>'.$text;
            }

            if(isset($b['link']) && $b['link']) {
                $text = "<a href='{$b['link']}'>$text</a>";
            }

            if($i === $last) {
                echo "<li class='active'>$text</li>";
                continue;
            }

            echo "<li>$text</li>";
        }
        ?>
    </ol>
<?php
}