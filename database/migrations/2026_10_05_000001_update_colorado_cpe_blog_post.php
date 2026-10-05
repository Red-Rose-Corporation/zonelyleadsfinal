<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Rewrites the Colorado CPE blog post to target "colorado rules and regulations
 * cpe course". Only name, short_description, keyword and description change; the
 * slug (which the post already ranks on), image, pageviews and owner are untouched.
 *
 * The previous content is saved to blog_content_backups so down() can restore it.
 * Safe to re-run: if the new content is already in place nothing happens, and if
 * the post does not exist nothing happens.
 */
return new class extends Migration
{
    private const SLUG = 'colorado-cpa-cpe-requirements-complete-guide-2025';

    public function up(): void
    {
        if (!Schema::hasTable('blogs')) {
            return;
        }

        $blog = DB::table('blogs')->where('slug', self::SLUG)->first();
        if (!$blog) {
            return;
        }

        $new = $this->newContent();

        if ($blog->name === $new['name'] && $blog->description === $new['description']) {
            return;
        }

        if (!Schema::hasTable('blog_content_backups')) {
            Schema::create('blog_content_backups', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('blog_id')->index();
                $table->string('name');
                $table->text('short_description')->nullable();
                $table->longText('description')->nullable();
                $table->longText('keyword')->nullable();
                $table->timestamp('created_at')->nullable();
            });
        }

        DB::transaction(function () use ($blog, $new) {
            DB::table('blog_content_backups')->insert([
                'blog_id'           => $blog->id,
                'name'              => $blog->name,
                'short_description' => $blog->short_description,
                'description'       => $blog->description,
                'keyword'           => $blog->keyword,
                'created_at'        => now(),
            ]);

            DB::table('blogs')->where('id', $blog->id)->update($new + ['updated_at' => now()]);
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('blogs') || !Schema::hasTable('blog_content_backups')) {
            return;
        }

        $blog = DB::table('blogs')->where('slug', self::SLUG)->first();
        if ($blog) {
            $backup = DB::table('blog_content_backups')->where('blog_id', $blog->id)->orderBy('id')->first();
            if ($backup) {
                DB::table('blogs')->where('id', $blog->id)->update([
                    'name'              => $backup->name,
                    'short_description' => $backup->short_description,
                    'description'       => $backup->description,
                    'keyword'           => $backup->keyword,
                    'updated_at'        => now(),
                ]);
            }
        }

        Schema::dropIfExists('blog_content_backups');
    }

    private function newContent(): array
    {
        return [
            'name'              => 'Colorado Rules and Regulations CPE Course (CR&R) 2026',
            'short_description' => 'What is the Colorado Rules and Regulations (CR&R) CPE course? See who must take it, the 2-hour ethics rule, what it covers and how to pick a valid one.',
            'keyword'           => 'colorado rules and regulations cpe course, colorado cr&r course, colorado cpa ethics cpe, colorado cpa cpe requirements',
            'description'       => <<<'HTML'
<p>The <strong>Colorado Rules and Regulations (CR&amp;R) CPE course</strong> is a continuing education course on Colorado&rsquo;s accountancy statutes and the rules and policies of the State Board of Accountancy. For most CPAs it is an <em>optional</em> way to earn up to <strong>2 of your 4 required ethics hours</strong>. For newly licensed CPAs it is <strong>required</strong>: 2 hours of CR&amp;R within six months of getting your initial certificate. <em>Last updated October 2026.</em></p>

<h2>Colorado CR&amp;R course: quick answers</h2>
<table style="width:100%;border-collapse:collapse;margin:1rem 0">
<tbody>
<tr><td style="border:1px solid #e2e8f0;padding:8px"><strong>What it is</strong></td><td style="border:1px solid #e2e8f0;padding:8px">CPE on the Colorado accountancy statutes, Board rules and Board policies</td></tr>
<tr><td style="border:1px solid #e2e8f0;padding:8px"><strong>Hours that count</strong></td><td style="border:1px solid #e2e8f0;padding:8px">Up to 2 hours of CR&amp;R toward your 4 ethics hours</td></tr>
<tr><td style="border:1px solid #e2e8f0;padding:8px"><strong>Required for</strong></td><td style="border:1px solid #e2e8f0;padding:8px">New certificate holders: 2 hours within 6 months before or after the initial certificate</td></tr>
<tr><td style="border:1px solid #e2e8f0;padding:8px"><strong>Counts as</strong></td><td style="border:1px solid #e2e8f0;padding:8px">Regulatory Ethics</td></tr>
<tr><td style="border:1px solid #e2e8f0;padding:8px"><strong>Current CPE period</strong></td><td style="border:1px solid #e2e8f0;padding:8px">January 1, 2026 &ndash; December 31, 2027</td></tr>
<tr><td style="border:1px solid #e2e8f0;padding:8px"><strong>Records</strong></td><td style="border:1px solid #e2e8f0;padding:8px">Keep at least 5 years from the end of the year you completed it</td></tr>
</tbody>
</table>

<h2>What is the Colorado Rules and Regulations (CR&amp;R) course?</h2>
<p>The Board of Accountancy defines CR&amp;R as continuing education covering sections 12-100-101 through 130 and 13-90-107(1)(f) of the Colorado Revised Statutes, plus the Board&rsquo;s rules and policies. The course teaches Colorado CPAs the law that governs their license: who can use the CPA title, how certificates are maintained, what counts as professional misconduct, and how firms must register.</p>
<p>CR&amp;R is treated as <strong>Regulatory Ethics</strong>, which is why it counts toward your ethics hours.</p>

<h2>Who has to take it?</h2>
<h3>New Colorado CPAs: required</h3>
<p>If you are granted an initial certificate, you must complete <strong>2 hours of CR&amp;R within six months after the date the Board grants it</strong>. A CR&amp;R course taken in the six months <em>before</em> the certificate date also satisfies this. A course taken outside that window will not meet the requirement, although it may still count as general CPE. This also applies to applicants who already hold a license from another state.</p>
<h3>Existing CPAs: optional</h3>
<p>Every Colorado CPA must complete 4 hours of ethics CPE in each reporting period, and <strong>up to 2 of those hours may be CR&amp;R</strong>. You can also meet all 4 hours with other approved ethics courses, so CR&amp;R is a choice, not a mandate, for experienced licensees.</p>
<h3>Reactivating or reinstating a certificate</h3>
<p>If you reactivate or reinstate your certificate, you must complete 2 hours of ethics CPE that <strong>cannot</strong> be CR&amp;R. Do not rely on a CR&amp;R course for this.</p>
<h3>Inactive certificates</h3>
<p>Holders of an inactive certificate do not need to meet CPE requirements while the certificate is inactive.</p>

<h2>How CR&amp;R fits into your 80 CPE hours</h2>
<p>An active Colorado CPA earns <strong>10 CPE hours for every full quarter</strong> the certificate is active, which is <strong>80 hours</strong> over a full two-year reporting period. Of those hours:</p>
<ul>
<li>At least <strong>4 hours must be ethics</strong>, and up to <strong>2 of the 4</strong> may be CR&amp;R.</li>
<li>No more than <strong>20%</strong> can be personal development (16 hours).</li>
<li>No more than <strong>50%</strong> can come from any combination of teaching or publishing an article or book (40 hours).</li>
</ul>
<p>The reporting period runs from <strong>January 1 of an even-numbered year to December 31 of the following odd-numbered year</strong>. The current period is January 1, 2026 to December 31, 2027, and CPE must be completed by December 31, 2027. Colorado CPA licenses expire on <strong>November 30 of odd-numbered years</strong>, so the next renewal is November 30, 2027, and you attest to meeting the CPE rules when you renew.</p>

<h2>What a CR&amp;R course covers</h2>
<p>The Board publishes a content outline that every CR&amp;R course must follow:</p>
<ul>
<li><strong>Overview of regulatory requirements:</strong> the Colorado Revised Statutes as they apply to accountancy, Board rules and policies, and recent legislative changes</li>
<li><strong>The State Board of Accountancy:</strong> its organization, duties and website</li>
<li><strong>The CPA designation:</strong> proper use of the title, types of certificates, certificate status and maintenance, licensure, CPE, disclosures, names, practice privilege and reciprocity, and peer review</li>
<li><strong>Professional conduct:</strong> unlawful acts, accountant-client privilege, grounds for discipline and client records</li>
<li><strong>Firms:</strong> registration, firm names, peer review and disclosures</li>
</ul>

<h2>How to choose a valid CR&amp;R course</h2>
<p>The Board will not accept a CR&amp;R course that does not cover the current statutes and Board rules and follow the content outline. Before you pay, check that:</p>
<ol>
<li>The course says it meets the <strong>Colorado Board of Accountancy CR&amp;R requirement</strong>, not just &ldquo;ethics.&rdquo;</li>
<li>It covers the <strong>current</strong> Colorado statutes, rules and policies, and the materials show <strong>the date the course was last updated</strong>. The Board requires this date on the course materials or certificate.</li>
<li>It follows the outline above.</li>
<li>You understand the hour limit: a longer course can still be offered, but <strong>only 2 hours count as CR&amp;R or ethics</strong>. Any hours beyond 2 count as Specialized Knowledge instead.</li>
</ol>
<p>Several CPE providers offer a CR&amp;R course, including Western CPE (a 2-hour course), Becker, Surgent and LearnCPE. These are examples, not endorsements. Check each provider&rsquo;s current catalog and confirm the course is current before you buy.</p>

<h2>Keep your certificate: documentation rules</h2>
<p>You are responsible for reporting and documenting every CPE hour. Keep records for <strong>at least five years from the end of the year you completed the course</strong>. Your certificate of completion should show:</p>
<ul>
<li>The sponsor&rsquo;s name and contact information</li>
<li>Your name</li>
<li>The program title and field of study</li>
<li>The date(s) completed, location (if any) and delivery method</li>
<li>The number of CPE credits</li>
<li>The sponsor&rsquo;s verification (signature, seal or similar)</li>
</ul>
<p>The Board may audit CPE records after a renewal period. If it asks for documentation, you must respond <strong>within 30 days</strong> of the request. If a review finds a shortfall, you have <strong>30 days from the notice</strong> to provide more evidence or documentation of additional qualifying hours completed during the reporting period. Failing to complete required CPE by December 31 can lead to discipline, up to revocation. If you are facing a Board complaint, see our guide to a <a href="/blog/cpa-license-defense-attorney-protecting-your-career-and-reputation">CPA license defense attorney</a>.</p>

<h2>Common mistakes</h2>
<ul>
<li>Taking a longer course and assuming every hour counts as ethics. Only 2 hours count as CR&amp;R.</li>
<li>Taking CR&amp;R outside the six-month window as a new licensee.</li>
<li>Using CR&amp;R to satisfy the reinstatement or reactivation ethics hours, where it does not count.</li>
<li>Buying an out-of-date course with no &ldquo;last updated&rdquo; date.</li>
<li>Losing the certificate, then being unable to answer an audit request in 30 days.</li>
</ul>

<h2>Frequently asked questions</h2>
<h3>Is the Colorado Rules and Regulations course required every cycle?</h3>
<p>No. For existing CPAs it is one way to earn up to 2 of the 4 ethics hours. It is required only for new certificate holders.</p>
<h3>How many hours is the CR&amp;R course?</h3>
<p>Most are 2 hours. A longer course is allowed, but only 2 hours count as CR&amp;R or ethics.</p>
<h3>Does CR&amp;R count as ethics?</h3>
<p>Yes. The 2 hours count as Regulatory Ethics toward your 4 required ethics hours.</p>
<h3>When must a new Colorado CPA take CR&amp;R?</h3>
<p>Within six months after the Board grants the initial certificate, or in the six months before it.</p>
<h3>Can I take a CR&amp;R course online?</h3>
<p>Yes. Several CPE providers sell online and self-study versions. Confirm the course meets the Colorado Board&rsquo;s CR&amp;R requirement before you buy.</p>
<h3>How long should I keep my CR&amp;R certificate?</h3>
<p>At least five years from the end of the year you completed the course. Submit it to the Board within 30 days if asked.</p>

<h2>Official sources</h2>
<p>Rules change, so always confirm with the <a href="https://dpo.colorado.gov/Accountancy_CPE" rel="noopener" target="_blank">Colorado State Board of Accountancy CPE page (DORA)</a>. The requirements above come from the Board&rsquo;s <em>Rules of the State Board of Accountancy</em> (3 CCR 705-1), Rules 1.6 and 1.7, published in the <a href="https://www.sos.state.co.us/CCR/" rel="noopener" target="_blank">Code of Colorado Regulations</a>. This guide is general information, not legal advice. For other states, see our <a href="/blog/nc-cpa-ethics-course">North Carolina CPA ethics course guide</a>.</p>

<h2>Are you a Colorado CPA or accounting firm?</h2>
<p>Zonely connects clients with verified local professionals. <a href="/user/register/seller">List your CPA practice for free</a> or browse <a href="/category/cpas-accountants">CPAs &amp; Accountants</a> near you.</p>
HTML,
        ];
    }
};
