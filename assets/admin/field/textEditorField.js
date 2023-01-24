import '../css/field/textEditorField.scss'

// Import TinyMCE
import tinymce from 'tinymce/tinymce'

// A theme is also required
import 'tinymce/themes/silver'
import 'tinymce/models/dom'
import 'tinymce/icons/default'

// Any plugins you want to use has to be imported
import 'tinymce/plugins/advlist'
import 'tinymce/plugins/autolink'
import 'tinymce/plugins/lists'
import 'tinymce/plugins/link'
import 'tinymce/plugins/image'
import 'tinymce/plugins/charmap'
import 'tinymce/plugins/preview'
import 'tinymce/plugins/anchor'
import 'tinymce/plugins/searchreplace'
import 'tinymce/plugins/visualblocks'
import 'tinymce/plugins/code'
import 'tinymce/plugins/fullscreen'
import 'tinymce/plugins/insertdatetime'
import 'tinymce/plugins/media'
import 'tinymce/plugins/table'
import 'tinymce/plugins/help'
import 'tinymce/plugins/wordcount'

document.addEventListener('DOMContentLoaded', () => {
    TextEditorField.init()
})

class TextEditorField {
    constructor() {}

    static init() {
        console.log('textEditorField')

        tinymce.init({
            selector: 'textarea.text-editor',
            model: 'dom',
			language: 'es',
			promotion: false,
            height: 500,
            plugins: [
                'advlist',
                'autolink',
                'lists',
                'link',
                'image',
                'charmap',
                'preview',
                'anchor',
                'searchreplace',
                'visualblocks',
                'code',
                'fullscreen',
                'insertdatetime',
                'media',
                'table',
                'help',
                'wordcount'
            ],
            toolbar:
                'undo redo | blocks | ' +
                'bold italic backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | help',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
        })
    }
}
