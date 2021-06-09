<?php

namespace GDaisy;

class TextPlaceholder implements PlaceholderInterface
{
    public int $pos_x;

    public int $pos_y;

    public string $font;

    public string $color;

    public ?int $max_width;

    public int $size;

    static int $spacing = 2;

    public function __construct(array $params = [])
    {
        $this->size = $params['size'] ?? 20;
        $this->pos_x = $params['pos_x'] ?? 0;
        $this->pos_y = $params['pos_y'] ?? $this->size;
        $font = $params['font'] ?? 'resources/fonts/OpenSans-Regular.ttf';
        $this->font = __DIR__ . '/../' . $font;
        $this->color = $params['color'] ?? "000000";
        $this->max_width = $params['max_width'] ?? null;
    }

    public function apply($resource, array $params = [])
    {
        $color = Util::getColor($resource, $this->color);
        $lines[] = $params['text'];
        $size = imagettfbbox($this->size, 0, $this->font, $params['text']);
        $width = $size[2] - $size[0];
        $height = ($size[7] - $size[1]) * -1;

        if ($this->max_width) {
            if ($width > $this->max_width) {
                $max_letters = (strlen($params['text']) * $this->max_width) / $width;
                $wrapped = wordwrap($params['text'], $max_letters, "#");
                $lines = explode("#", $wrapped);
            }
        }

        $count = 1;
        foreach ($lines as $text) {
            $pos_y = $this->pos_y + ($count * $height) + self::$spacing;
            imagettftext($resource, $this->size, 0, $this->pos_x, $pos_y, $color, $this->font, $text);
            $count++;
        }
    }
}