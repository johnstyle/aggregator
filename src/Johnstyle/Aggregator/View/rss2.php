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

use Johnstyle\Aggregator\Aggregator;

?>
<?xml version="1.0"?>
<rss version="2.0">
    <channel>
        <title><?php echo $title; ?></title>
        <description><?php echo $description; ?></description>
        <language><?php echo Aggregator::LANGUAGE; ?></language>
        <pubDate><?php echo Aggregator::getCurrentDate(); ?></pubDate>
        <lastBuildDate><?php echo Aggregator::getCurrentDate(); ?></lastBuildDate>
        <?php foreach($items as $item): ?>
            <item>
                <title><?php echo $item->title; ?></title>
                <link><?php echo $item->link; ?></link>
                <description><?php echo $item->description; ?></description>
                <pubDate><?php echo $item->pubDate; ?></pubDate>
                <guid><?php echo $item->guid; ?></guid>
                <?php if(count($item->categories)): ?>
                    <?php foreach($item->categories as $category): ?>
                        <category><?php echo $category; ?></category>
                    <?php endforeach; ?>
                <?php endif; ?>
                <?php if(count($item->enclosures)): ?>
                    <?php foreach($item->enclosures as $enclosure): ?>
                        <enclosure url="<?php echo $enclosure['url']; ?>" length="<?php echo $enclosure['length']; ?>" type="<?php echo $enclosure['type']; ?>" />
                    <?php endforeach; ?>
                <?php endif; ?>
            </item>
        <?php endforeach; ?>
    </channel>
</rss>
