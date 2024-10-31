<?php do_action( 'ncm_email_header', __("HTML email template", NCM_txt_domain) ); ?>

<table border="0" cellpadding="20" cellspacing="0" width="100%">
  <tbody>
    <tr>
      <td valign="top" style="padding: 48px 48px 0;">
        <div id="body_content_inner" style="color:<?php echo $ncm_email_text_color; ?>; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; font-size: 14px; line-height: 150%; text-align: left;">
          <p style="margin: 0 0 16px;">
            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. 
          </p>

          <p style="margin: 0 0 16px;">
            It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
          </p>

          <h2 style="color:<?php echo $ncm_email_text_color; ?>; display: block; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; font-size: 18px; font-weight: bold; line-height: 130%; margin: 0 0 18px; text-align: left;">
            Lorem ipsum dolor
          </h2>

          <p style="margin: 0 0 16px;">
            It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable
          </p>
        </div>
      </td>
    </tr>
  </tbody>
</table>

<?php do_action( 'ncm_email_footer' ); ?>