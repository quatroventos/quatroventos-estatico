import { useEditor, EditorContent } from '@tiptap/react';
import StarterKit from '@tiptap/starter-kit';
import Underline from '@tiptap/extension-underline';
import Link from '@tiptap/extension-link';
import { TextStyle } from '@tiptap/extension-text-style';
import { Color } from '@tiptap/extension-color';
import { Bold, Italic, Link as LinkIcon, List, ListOrdered, Strikethrough, Underline as UnderlineIcon } from 'lucide-react';
import { useEffect } from 'react';

const ACCENT = '#f45a20';

export function RichText({
  value,
  onChange,
}: {
  value: string;
  onChange: (value: string) => void;
}) {
  const editor = useEditor({
    extensions: [
      StarterKit.configure({ heading: false }),
      Underline,
      TextStyle,
      Color,
      Link.configure({ openOnClick: false }),
    ],
    content: value || '',
    onUpdate: ({ editor: current }) => {
      onChange(current.getHTML() === '<p></p>' ? '' : current.getHTML());
    },
  });

  useEffect(() => {
    if (!editor) {
      return;
    }
    const current = editor.getHTML();
    const next = value || '';
    if (next !== current && next !== '' && current === '<p></p>') {
      editor.commands.setContent(next);
    }
  }, [value, editor]);

  if (!editor) {
    return null;
  }

  const accentOn = editor.isActive('textStyle', { color: ACCENT });

  return (
    <div className="rich-wrap">
      <div className="rich-toolbar">
        <button type="button" className={editor.isActive('bold') ? 'is-active' : ''} onClick={() => editor.chain().focus().toggleBold().run()}><Bold size={14} /></button>
        <button type="button" className={editor.isActive('italic') ? 'is-active' : ''} onClick={() => editor.chain().focus().toggleItalic().run()}><Italic size={14} /></button>
        <button type="button" className={editor.isActive('underline') ? 'is-active' : ''} onClick={() => editor.chain().focus().toggleUnderline().run()}><UnderlineIcon size={14} /></button>
        <button type="button" className={editor.isActive('strike') ? 'is-active' : ''} onClick={() => editor.chain().focus().toggleStrike().run()}><Strikethrough size={14} /></button>
        <button type="button" className={editor.isActive('bulletList') ? 'is-active' : ''} onClick={() => editor.chain().focus().toggleBulletList().run()}><List size={14} /></button>
        <button type="button" className={editor.isActive('orderedList') ? 'is-active' : ''} onClick={() => editor.chain().focus().toggleOrderedList().run()}><ListOrdered size={14} /></button>
        <button
          type="button"
          className={editor.isActive('link') ? 'is-active' : ''}
          onClick={() => {
            const href = window.prompt('Link');
            if (href) {
              editor.chain().focus().setLink({ href }).run();
            }
          }}
        >
          <LinkIcon size={14} />
        </button>
        <label className={`rich-color${accentOn ? ' is-active' : ''}`} title="Cor do texto">
          <span className="rich-color-swatch" style={{ background: editor.getAttributes('textStyle').color || '#14253d' }} />
          <input
            type="color"
            value={editor.getAttributes('textStyle').color || ACCENT}
            onChange={(event) => editor.chain().focus().setColor(event.target.value).run()}
          />
        </label>
        <button
          type="button"
          className={accentOn ? 'is-active' : ''}
          title="Laranja da marca"
          onClick={() => {
            if (accentOn) {
              editor.chain().focus().unsetColor().run();
            } else {
              editor.chain().focus().setColor(ACCENT).run();
            }
          }}
        >
          <span className="rich-color-swatch" style={{ background: ACCENT }} />
        </button>
      </div>
      <EditorContent editor={editor} />
    </div>
  );
}
