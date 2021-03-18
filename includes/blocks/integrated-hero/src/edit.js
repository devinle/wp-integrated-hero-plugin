/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';
import { useDispatch, useSelect } from '@wordpress/data';
import { MediaUpload, MediaUploadCheck, RichText } from '@wordpress/block-editor';
import { Button, CheckboxControl } from '@wordpress/components';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @param {Object} [props]           Properties passed from the editor.
 * @param {string} [props.className] Class name generated for the block.
 *
 * @return {WPElement} Element to render.
 */
export default function Edit( { attributes, className, isSelected, setAttributes } ) {
	// Setters
	const {editPost} = useDispatch('core/editor');
	const setPostTitle = (title) => editPost({ title });
	const setPostExcerpt = (excerpt) => editPost({ excerpt });
	const setPostImage = ({ id = 0 }) => editPost({ featured_media: id });

	// Getters
	const getPostTitle = useSelect((select) => select('core/editor').getEditedPostAttribute('title'));
	const getPostExcerpt = useSelect((select) => select('core/editor').getEditedPostAttribute('excerpt'));
	const getFeaturedImageId = useSelect((select) => select('core/editor').getEditedPostAttribute('featured_media'));
	const getMedia = useSelect((select) => select('core').getMedia(getFeaturedImageId));

	const contentClass = `${className}__content`;

	const { enhancedHero } = attributes;

	const setChecked = (newVal) => setAttributes({ enhancedHero: newVal });

	return (
		<>
			<CheckboxControl
				className="wp-block"
            	label="Enhanced Hero"
            	checked={ enhancedHero }
            	onChange={ setChecked }
			/>

			{
			enhancedHero &&
			<header className={className}>

				<style
					dangerouslySetInnerHTML={{
						__html: `.edit-post-visual-editor__post-title-wrapper { display: none }`,
					}}
				/>

				{
					getMedia &&
					<img src={getMedia.source_url} />
				}

				<div class={contentClass}>

					{isSelected &&
						<>
						<RichText
							className="wp-block-integrated-hero-block-integrated-hero__title"
							tagName="h1"
							formattingControls={[]}
							value={getPostTitle}
							onChange={(updatedTitle) => setPostTitle(updatedTitle)}
							placeholder={ __( 'Heading...' ) }
						/>
						<RichText
							className="wp-block-integrated-hero-block-integrated-hero__subtitle"
							tagName="p"
							formattingControls={[]}
							value={getPostExcerpt}
							onChange={(updatedExcerpt) => setPostExcerpt(updatedExcerpt)}
							placeholder={ __( 'Excerpt...' ) }
						/>
						</>
					}

					{!isSelected &&
						<>
						<h1 className="wp-block-integrated-hero-block-integrated-hero__title">{getPostTitle}</h1>
						<p>{getPostExcerpt}</p>
						</>
					}

					{isSelected &&
						<div class="media-controls">
							<MediaUploadCheck>
								<MediaUpload
									title={__('Select a Hero Image', 'hero')}
									onSelect={(media) => setPostImage(media)}
									allowedTypes={['image']}
									render={({open})=>(
										<Button
											className={getFeaturedImageId === 0 ? 'editor-post-featured-image__toggle' : 'editor-post-featured-image__preview'}
											onClick={(open)}
										>
											{getFeaturedImageId === 0 && __('Choose a Hero Image', 'hero')}
										</Button>
									)}
								/>
							</MediaUploadCheck>

							{getFeaturedImageId !== 0 &&
								<MediaUploadCheck>
									<Button
										onClick={() => setPostImage({id:0})}
										isLink
										isDestructive
									>{__('Remove Hero Image', 'hero')}</Button>
								</MediaUploadCheck>
							}
						</div>
					}

				</div>


			</header>
			}
		</>
	);
}
