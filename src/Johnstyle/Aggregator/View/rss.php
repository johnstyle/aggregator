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
<rss version="2.0">
    <channel>
        <title><?php echo $options['title']; ?></title>
        <description><?php echo $options['description']; ?></description>
        <language><?php echo $this->language; ?></language>
        <pubDate><?php echo date('r'); ?></pubDate>
        <lastBuildDate><?php echo date('r'); ?></lastBuildDate>
        <?php foreach($this->items as $item): ?>
            <item>
                <title><![CDATA[<?php echo $item['title']; ?>]]></title>
                <link><?php echo $item['link']; ?></link>
                <guid><?php echo $item['guid']; ?></guid>
                <pubDate><?php echo $item['pubDate']; ?></pubDate>
                <description><![CDATA[<?php echo $item['description']; ?>]]></description>
                <?php if(count($item['categories'])): ?>
                    <?php foreach($item['categories'] as $category): ?>
                        <category><![CDATA[<?php echo $category; ?>]]></category>
                    <?php endforeach; ?>
                <?php endif; ?>
                <?php if(count($item['enclosures'])): ?>
                    <?php foreach($item['enclosures'] as $enclosure): ?>
                        <enclosure url="<?php echo $enclosure['url']; ?>" length="<?php echo $enclosure['length']; ?>" type="<?php echo $enclosure['type']; ?>"/>
                    <?php endforeach; ?>
                <?php endif; ?>
            </item>
        <?php endforeach; ?>
    </channel>
</rss>
