<?php
/**
 * Aggregator
 *
 * PHP version 5
 *
 * @package  Johnstyle\Aggregator
 * @author   Jonathan Sahm <contact@johnstyle.fr>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://github.com/johnstyle/aggregator
 */
?>
<?xml version="1.0" encoding="<?php echo $this->charset; ?>"?>
<feed xmlns="http://www.w3.org/2005/Atom">
    <id><?php echo $options['title']; ?></id>
    <title><?php echo $options['title']; ?></title>
    <description><?php echo $options['description']; ?></description>
    <updated><?php echo date('c'); ?></updated>
    <?php foreach($this->items as $item): ?>
        <entry>
            <title><?php echo $item['title']; ?></title>
            <link rel="alternate" type="text/html" href="<?php echo $item['link']; ?>"/>
            <id><?php echo $item['guid']; ?></id>
            <published><?php echo $item['pubDate']; ?></published>
            <updated><?php echo $item['pubDate']; ?></updated>
            <content><?php echo $item['description']; ?></content>
            <?php if(count($item['categories'])): ?>
                <?php foreach($item['categories'] as $category): ?>
                    <category term="<?php echo $category; ?>"/>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if(count($item['enclosures'])): ?>
                <?php foreach($item['enclosures'] as $enclosure): ?>
                    <link rel="enclosure" href="<?php echo $enclosure['url']; ?>" length="<?php echo $enclosure['length']; ?>" type="<?php echo $enclosure['type']; ?>"/>
                <?php endforeach; ?>
            <?php endif; ?>
        </entry>
    <?php endforeach; ?>
</feed>
