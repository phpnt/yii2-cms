<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 28.10.2018
 * Time: 19:57
 */

namespace common\models\extend;

use Yii;
use common\models\forms\MessageForm;
use common\models\SourceMessage;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\console\Exception;
use yii\helpers\Console;
use yii\helpers\Json;

class SourceMessageExtend extends SourceMessage
{
    /**
     * @return array|SourceMessage[]
     */
    public static function getCategories()
    {
        $modelSourceMessage = SourceMessage::find()
            ->select('category')
            ->distinct('category')
            ->asArray()
            ->all()
        ;
        $arrayCategories = ArrayHelper::map($modelSourceMessage, 'category', 'category');
        return $arrayCategories;
    }

    /**
     * Extracts messages to be translated from source code.
     *
     * This command will search through source code files and extract
     * messages that need to be translated in different languages.
     *
     * @throws Exception on failure.
     * @return array
     */
    public function extract()
    {
        if (!isset($this->config['sourcePath'], $this->config['languages'])) {
            throw new Exception('The configuration must specify "sourcePath" and "languages".');
        }

        foreach ($this->config['sourcePath'] as $sourcePath) {
            if (!is_dir($sourcePath)) {
                throw new Exception("The source path {$sourcePath} is not a valid directory.");
            }
        }

        if (empty($this->config['format']) || !in_array($this->config['format'], ['php', 'po', 'db'])) {
            throw new Exception('Format should be either "php", "po" or "db".');
        }
        if (in_array($this->config['format'], ['php', 'po'])) {
            if (!isset($this->config['messagePath'])) {
                throw new Exception('The configuration file must specify "messagePath".');
            } elseif (!is_dir($this->config['messagePath'])) {
                throw new Exception("The message path {$this->config['messagePath']} is not a valid directory.");
            }
        }
        if (empty($this->config['languages'])) {
            throw new Exception("Languages cannot be empty.");
        }

        $files = [];
        foreach ( $this->config['sourcePath'] as $sourcePath ) {
            $files = array_merge(
                array_values($files),
                array_values(FileHelper::findFiles(realpath($sourcePath), $this->config))
            );
        }

        $messages = [];
        foreach ($files as $file) {
            $messages = array_merge_recursive($messages, $this->extractMessages($file, $this->config['translator']));
        }

        $db = Yii::$app->get(isset($this->config['db']) ? $this->config['db'] : 'db');
        if (!$db instanceof \yii\db\Connection) {
            throw new Exception('The "db" option must refer to a valid database application component.');
        }
        $sourceMessageTable = isset($this->config['sourceMessageTable']) ? $this->config['sourceMessageTable'] : '{{%source_message}}';
        $messageTable = isset($this->config['messageTable']) ? $this->config['messageTable'] : '{{%message}}';

        $documents = (new \yii\db\Query())
            ->select(['*'])
            ->from('document')
            ->all();

        foreach ($documents as $document) {
            if ($document['name']) {
                $messages['app'][] = $document['name'];
                $this->locations['app'][] = [
                    md5($document['name']) => 'Table "document" field "name", ID = ' . $document['id']
                ];
            }
            if ($document['title']) {
                $messages['app'][] = $document['title'];
                $this->locations['app'][] = [
                    md5($document['title']) => 'Table "document" field "title", ID = ' . $document['id']
                ];
            }
            if ($document['annotation']) {
                $messages['app'][] = $document['annotation'];
                $this->locations['app'][] = [
                    md5($document['annotation']) => 'Table "document" field "annotation", ID = ' . $document['id']
                ];
            }
            if ($document['content']) {
                $messages['app'][] = $document['content'];
                $this->locations['app'][] = [
                    md5($document['content']) => 'Table "document" field "content", ID = ' . $document['id']
                ];
            }
        }

        $fields = (new \yii\db\Query())
            ->select(['*'])
            ->from('field')
            ->all();

        foreach ($fields as $field) {
            if ($field['name']) {
                $messages['app'][] = $field['name'];
                $this->locations['app'][] = [
                    md5($field['name']) => 'Table "field" field "name", ID = ' . $field['id']
                ];
            }
        }

        $templates = (new \yii\db\Query())
            ->select(['*'])
            ->from('template')
            ->all();

        foreach ($templates as $template) {
            if ($template['name']) {
                $messages['app'][] = $template['name'];
                $this->locations['app'][] = [
                    md5($template['name']) => 'Table "template" field "name", ID = ' . $template['id']
                ];
            }
            if ($template['description']) {
                $messages['app'][] = $template['description'];
                $this->locations['app'][] = [
                    md5($template['description']) => 'Table "template" field "description", ID = ' . $template['id']
                ];
            }
        }

        $valueStrings = (new \yii\db\Query())
            ->select(['*'])
            ->from('value_string')
            ->all();

        foreach ($valueStrings as $valueString) {
            if ($valueString['title']) {
                $messages['app'][] = $valueString['title'];
                $this->locations['app'][] = [
                    md5($valueString['title']) => 'Table "value_string" field "title", ID = ' . $valueString['id']
                ];
            }
            if ($valueString['value']) {
                $messages['app'][] = $valueString['value'];
                $this->locations['app'][] = [
                    md5($valueString['value']) => 'Table "value_string" field "value", ID = ' . $valueString['id']
                ];
            }
        }

        $valueTexts = (new \yii\db\Query())
            ->select(['*'])
            ->from('value_text')
            ->all();

        foreach ($valueTexts as $valueText) {
            if ($valueText['title']) {
                $messages['app'][] = $valueText['title'];
                $this->locations['app'][] = [
                    md5($valueText['title']) => 'Table "value_text" field "title", ID = ' . $valueText['id']
                ];
            }
            if ($valueText['value']) {
                $messages['app'][] = $valueText['value'];
                $this->locations['app'][] = [
                    md5($valueText['value']) => 'Table "value_text" field "value", ID = ' . $valueText['id']
                ];
            }
        }

        $result = [];
        foreach ($messages['app'] as $message) {
            if (preg_match("/[а-яё]/iu", $message)) {
                $result['app'][] = $message;
            }
        }

        /*d($this->locations);
        dd([$messages, $db, $sourceMessageTable, $messageTable, $this->config['removeUnused'], $this->config['languages']]);*/

        return $this->saveMessagesToDb(
            $result,
            $db,
            $sourceMessageTable,
            $messageTable,
            $this->config['removeUnused'],
            $this->config['languages']
        );
    }

    /**
     * Saves messages to database
     *
     * @param array $messages
     * @param \yii\db\Connection $db
     * @param string $sourceMessageTable
     * @param string $messageTable
     * @param boolean $removeUnused
     * @param array $languages
     */
    public function saveMessagesToDb($messages, $db, $sourceMessageTable, $messageTable, $removeUnused, $languages)
    {
        $q = new \yii\db\Query;
        $current = [];

        foreach ($q->select(['id', 'category', 'message'])->from($sourceMessageTable)->all() as $row) {
            $current[$row['category']][$row['id']] = $row['message'];
        }

        /* Запись местоположения во все пустые ячейки */
        $newMessages = 0;
        $msgHash = md5(time());

        foreach ($messages as $category => $msgs) {
            if ($category != 'yii') {
                foreach ($msgs as $m) {
                    $modelSourceMessage = ($modelSourceMessage = SourceMessage::find()
                        ->where([
                            'message' => $m,
                        ])
                        ->one()) ? $modelSourceMessage : new SourceMessage();
                    if ($modelSourceMessage->isNewRecord) {
                        $modelSourceMessage->category = $category;
                        $modelSourceMessage->message = $m;
                        $modelSourceMessage->hash = $msgHash;
                        $modelSourceMessage->location = $this->extractLocations($category, $m);
                        $modelSourceMessage->save();
                        $newMessages++;
                    } else {
                        $modelSourceMessage->hash = $msgHash;
                        $modelSourceMessage->save();
                    }
                }
            }
        }

        $modelSourceMessage = SourceMessage::find()
            ->where(['!=', 'hash', $msgHash])
            ->andWhere(['!=', 'category', 'yii'])
            ->count();

        SourceMessage::deleteAll('hash != :hash AND category != :category', [':hash' => $msgHash, ':category' => 'yii']);

        return ['new' => $newMessages, 'deleted' => $modelSourceMessage];
    }

    /**
     * @param string $category
     * @param string $message
     * @return string
     */
    protected function extractLocations($category, $message)
    {
        $output  = [];
        $msgHash = md5($message);

        foreach ( $this->locations[$category] as $location ) {
            if ( isset($location[$msgHash]) ) {
                $output[] = $location[$msgHash];
            }
        }

        return Json::encode($output);
    }

    /**
     * Extracts messages from a file
     *
     * @param string $fileName name of the file to extract messages from
     * @param string $translator name of the function used to translate messages
     * @return array
     */
    protected function extractMessages($fileName, $translator)
    {
        $coloredFileName = Console::ansiFormat($fileName, [Console::FG_CYAN]);
        $this->stdout("Extracting messages from $coloredFileName...\n");

        $subject  = file_get_contents($fileName);
        $messages = [];
        foreach ((array)$translator as $currentTranslator) {
            $translatorTokens = token_get_all('<?php ' . $currentTranslator);
            array_shift($translatorTokens);

            $translatorTokensCount = count($translatorTokens);
            $matchedTokensCount = 0;
            $buffer = [];

            $tokens = token_get_all($subject);
            foreach ($tokens as $token) {
                // finding out translator call
                if ($matchedTokensCount < $translatorTokensCount) {
                    if ($this->tokensEqual($token, $translatorTokens[$matchedTokensCount])) {
                        $matchedTokensCount++;
                    } else {
                        $matchedTokensCount = 0;
                    }
                } elseif ($matchedTokensCount === $translatorTokensCount) {
                    // translator found
                    // end of translator call or end of something that we can't extract
                    if ($this->tokensEqual(')', $token)) {
                        if (isset($buffer[0][0], $buffer[1], $buffer[2][0]) && $buffer[0][0] === T_CONSTANT_ENCAPSED_STRING && $buffer[1] === ',' && $buffer[2][0] === T_CONSTANT_ENCAPSED_STRING) {
                            // is valid call we can extract

                            $category = stripcslashes($buffer[0][1]);
                            $category = mb_substr($category, 1, mb_strlen($category) - 2);

                            $message = stripcslashes($buffer[2][1]);
                            $message = mb_substr($message, 1, mb_strlen($message) - 2);

                            $messages[$category][] = $message;
                            foreach ($this->config['sourcePath'] as $sourcePath) {
                                $location = str_replace(realpath($sourcePath), '', $fileName);
                                if ( $location !== $fileName ) {
                                    $parts = explode('/', $sourcePath);
                                    $key   = count($parts) - 1;
                                    $this->locations[$category][] = [md5($message) => $parts[$key] . $location];
                                }
                            }
                        } else {
                            // invalid call or dynamic call we can't extract
                            $line = Console::ansiFormat($this->getLine($buffer), [Console::FG_CYAN]);
                            $skipping = Console::ansiFormat('Skipping line', [Console::FG_YELLOW]);
                            $this->stdout("$skipping $line. Make sure both category and message are static strings.\n");
                        }

                        // prepare for the next match
                        $matchedTokensCount = 0;
                        $buffer = [];
                    } elseif ($token !== '(' && isset($token[0]) && !in_array($token[0], [T_WHITESPACE, T_COMMENT])) {
                        // ignore comments, whitespaces and beginning of function call
                        $buffer[] = $token;
                    }
                }
            }
        }

        return $messages;
    }

    /**
     * Finds out if two PHP tokens are equal
     *
     * @param array|string $a
     * @param array|string $b
     * @return boolean
     * @since 2.0.1
     */
    protected function tokensEqual($a, $b)
    {
        if (is_string($a) && is_string($b)) {
            return $a === $b;
        } elseif (isset($a[0], $a[1], $b[0], $b[1])) {
            return $a[0] === $b[0] && $a[1] == $b[1];
        }

        return false;
    }

    /**
     * Finds out a line of the first non-char PHP token found
     *
     * @param array $tokens
     * @return int|string
     * @since 2.0.1
     */
    protected function getLine($tokens)
    {
        foreach ($tokens as $token) {
            if (isset($token[2])) {
                return $token[2];
            }
        }

        return 'unknown';
    }

    /**
     * Prints a string to STDOUT
     *
     * You may optionally format the string with ANSI codes by
     * passing additional parameters using the constants defined in [[\yii\helpers\Console]].
     *
     * Example:
     *
     * ~~~
     * $this->stdout('This will be red and underlined.', Console::FG_RED, Console::UNDERLINE);
     * ~~~
     *
     * @param string $string the string to print
     * @return int|boolean Number of bytes printed or false on error
     */
    public function stdout($string)
    {
        if ( \Yii::$app->id != 'app-console' )
            return false;

        if ($this->isColorEnabled()) {
            $args = func_get_args();
            array_shift($args);
            $string = Console::ansiFormat($string, $args);
        }

        return Console::stdout($string);
    }

    /**
     * Returns a value indicating whether ANSI color is enabled.
     *
     * ANSI color is enabled only if [[color]] is set true or is not set
     * and the terminal supports ANSI color.
     *
     * @param resource $stream the stream to check.
     * @return boolean Whether to enable ANSI style in output.
     */
    public function isColorEnabled($stream = \STDOUT)
    {
        return $this->color === null ? Console::streamSupportsAnsiColors($stream) : $this->color;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(MessageForm::className(), ['id' => 'id'])->indexBy('language');
    }
}