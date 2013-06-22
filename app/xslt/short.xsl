<?xml version="1.0"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
	<xsl:output method="html" omit-xml-declaration="yes" encoding="ISO-8859-1"/>
	
	<!-- This stylesheet does output a full HTML Document. The beginning and end of the document can be found in docheader.php and footer.php -->

	<!--Template for root Element-->
	<xsl:template match="/">
		
		<p class="title_heading">
			<xsl:value-of select="descendant::mepHeader/docAuthor"/>, "<xsl:apply-templates select="descendant::mepHeader/docTitle/titlePart"/>," <xsl:apply-templates select="descendant::mepHeader/docDate"/>.
		</p>
		
		<p>
		 <xsl:value-of select="doc/@type"/>. Source: <xsl:apply-templates select="doc/mepHeader/sourceDesc"/>.
		</p>
		
		<p class="headnote">
			<xsl:apply-templates select="doc/headNote/p"/>
		</p>
		
		<hr />
	
		<!-- Main content of the document-->
	
		<xsl:apply-templates/>
	
		<!-- Footer -->
		
		<hr />
		
		<p>
		 	Subject Terms:
		</p>
		<ul>
			<xsl:apply-templates select="descendant::headNote/index">
			 	<xsl:sort data-type="text" select="translate(@level1,'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz')"/>
			  	<xsl:sort data-type="text" select="translate(@level2,'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz')"/>
			   	<xsl:sort data-type="text" select="translate(@level3,'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz')"/>
			    <xsl:sort data-type="text" select="translate(@level4,'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz')"/>
			</xsl:apply-templates>
		</ul>
		<p>
		 Copyright 2003. Margaret Sanger Project
        </p>
	</xsl:template>
	
	<!-- End of the root template-->
	
	<!-- Templates for the four second-level elements-->
	
	<xsl:template match="mepHeader">
		<!--don't display-->
	</xsl:template>
	
	<xsl:template match="sourceNote">
		<!--don't display-->
	</xsl:template>
	
	<xsl:template match="headNote">
		<!--don't display-->
	</xsl:template>
	
	<xsl:template match="docBody">
		<xsl:apply-templates/>
	</xsl:template>
	
	<!-- Templates for sepcific elements -->
		
	<xsl:template match="head">
		<xsl:if test="not(@type='sub')">
			<xsl:if test="@rend">
				<h1 class="{@rend}">
					<xsl:apply-templates/>
				</h1>
			</xsl:if>
			<xsl:if test="not(@rend)">
				<h1>
					<xsl:apply-templates/>
				</h1>
			</xsl:if>
		</xsl:if>
		<xsl:if test="@type='sub'">
			<xsl:if test="@rend">
				<h2 class="{@rend}">
					<xsl:apply-templates/>
				</h2>
			</xsl:if>
			<xsl:if test="not(@rend)">
				<h2>
					<xsl:apply-templates/>
				</h2>
			</xsl:if>
		</xsl:if>
	</xsl:template>
	
	<xsl:template match="docBody/p">
		<xsl:if test="@rend">
			<p class="{@rend}">
				<xsl:apply-templates/>
			</p>
		</xsl:if>
		<xsl:if test="not(@rend)">
			<p>
				<xsl:apply-templates/>
			</p>
		</xsl:if>
	</xsl:template>
	
		<xsl:template match="docBody/div/p">
		<xsl:if test="@rend">
			<p class="{@rend}">
				<xsl:apply-templates/>
			</p>
		</xsl:if>
		<xsl:if test="not(@rend)">
			<p>
				<xsl:apply-templates/>
			</p>
		</xsl:if>
	</xsl:template>
	
	<xsl:template match="sourceDesc">
		<xsl:apply-templates/>
	</xsl:template>

	<xsl:template match="mepHeader/sourceDesc/bibl">
	<xsl:if test="position()=2">
	<xsl:apply-templates/>
	</xsl:if>
	<xsl:if test="position()=4">
	, <xsl:apply-templates/>
	</xsl:if>
	</xsl:template>
	
	<xsl:template match="mepHeader/sourceDesc/bibl/title">
		<span class="italics">
			<xsl:apply-templates/>
		</span>
	</xsl:template>
	
	<xsl:template match="index">
		<li> 
			<a> <!--This rather convoluted code makes subject headings clickable--> 
				<xsl:variable name="level1" select="translate(@level1,'&quot;','^')"/> 

				<xsl:attribute name="href">search.php?subject=<xsl:value-of select="$level1"/></xsl:attribute> 
				<xsl:apply-templates select="@level1"/>
			</a>
			<a>
				<xsl:attribute name="href">search.php?subject=<xsl:value-of select="@level1"/>&amp;subject2=<xsl:value-of select="@level2"/></xsl:attribute> 
				<xsl:apply-templates select="@level2"/>
			</a>
			<a>
				<xsl:attribute name="href">search.php?subject=<xsl:value-of select="@level1"/>&amp;subject2=<xsl:value-of select="@level2"/>&amp;subject3=<xsl:value-of select="@level3"/></xsl:attribute> 
				<xsl:apply-templates select="@level3"/>
			</a>
			<a>
				<xsl:attribute name="href">search.php?subject=<xsl:value-of select="@level1"/>&amp;subject2=<xsl:value-of select="@level2"/>&amp;subject3=<xsl:value-of select="@level3"/>&amp;subject4=<xsl:value-of select="@level4"/></xsl:attribute> 
			<xsl:apply-templates select="@level4"/>
			</a>
		</li>
	</xsl:template>
	
	<xsl:template match="@level1">
		<xsl:value-of select="."/>
	</xsl:template>
	
	<xsl:template match="@level2">
		<xsl:text>, </xsl:text>
		<xsl:value-of select="."/>
	</xsl:template>
	
	<xsl:template match="@level3">
		<xsl:text>, </xsl:text>
		<xsl:value-of select="."/>
	</xsl:template>

	<xsl:template match="@level4">
        <xsl:text>, </xsl:text>
        <xsl:value-of select="."/>
     </xsl:template>	
	
	<xsl:template match="del">
		<span class="del">
			<xsl:apply-templates/>
		</span>
	</xsl:template>
	
	<xsl:template match="add">
       &#8593;<xsl:apply-templates/>&#8595;
    </xsl:template>
	
	<xsl:template match="supplied">
        [<em>
			<xsl:apply-templates/>
		</em>]
    </xsl:template>
	
	<xsl:template match="unclear">
        [<em>
			<xsl:apply-templates/>
		</em>?]
    </xsl:template>
	
	<xsl:template match="epigraph/title">
		<p class="epigraph-title">
			<xsl:apply-templates/>
		</p>
	</xsl:template>
	
	<xsl:template match="epigraph/p">
		<div class="epigraph-line">
			<xsl:apply-templates/>
		</div>
	</xsl:template>
	
	<xsl:template match="emph">
		<span class="{@rend}">
			<xsl:apply-templates/>
		</span>
	</xsl:template>
	
	<xsl:template match="hi">
		<span class="{@rend}">
			<xsl:apply-templates/>
		</span>
	</xsl:template>
	
	<xsl:template match="byline">
		<xsl:if test="@rend">
			<h3 class="{@rend}">
				<xsl:apply-templates/>
			</h3>
		</xsl:if>
		<xsl:if test="not(@rend)">
			<h3>
				<xsl:apply-templates/>
			</h3>
		</xsl:if>
	</xsl:template>
	
	<xsl:template match="title">
		<xsl:if test="@rend">
			<span class="{@rend}">
				<xsl:apply-templates/>
			</span>
		</xsl:if>
		<xsl:if test="not(@rend)">
			<xsl:apply-templates/>
		</xsl:if>
	</xsl:template>
	

	<xsl:template match="xref">
		<a href="show.php?sangerDoc={@from}">
			<xsl:apply-templates/>
		</a>
	</xsl:template>
	
	<xsl:template match="gap">
	[<em><xsl:value-of select="@extent" /><xsl:text> </xsl:text><xsl:value-of select="@reason" /></em>]
	</xsl:template>
	
	<xsl:template match="lb">
		<br />
	</xsl:template>
	
	<!-- End of Stylesheet -->

</xsl:stylesheet>
