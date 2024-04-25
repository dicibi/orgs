# The Orgs.

This package following standard of corporate structure. It doesn't include the default structure or names, you define it yourself. This package provide only the functionalities to organize, manage, and doing business process related to job structure.

The basic is to understand the definition of Job Title, Job Function, and Job Structure.

1. Job Title (some refer this as Job Level): a position name within the organization.
2. Job Function: more detailed description of Job Title for specific person.
3. Job Structure: a hierarchy that consist of Job Titles.
4. Job Family: a group of job titles with similar nature.

> Because it's f#cking enterprise thingy, so it'll have different terms within each organization. But here, what you need to know is the technical terms in this package, and you, yes you, you need to be aware and compare to ensure if this technical terms will fit or not with your business process (instead of blaming this package).

To understand more, here I give you a little example.

> Job Title: usually used by external actors to understand your position in general. Let's say that the company define you as 'Senior Software Engineer', but it's not specific and not define what you actually do. What people actually know is, 'Senior Software Engineer' is above the 'Mid-Software Engineer' and 'Software Engineer' in your company.

> Job Function: define your actual day-to-day job internally. Your Job Title is 'Senior Software Engineer' and your Job Function is 'Support & Operation Engineer' or something like 'Projects & Development' and the actual Job Function that describe you is 'Senior Software Engineer - Projects & Development' (Job Title + Job Function).

I'll give you an example of another use case in non-tech corporate.

> Job Title: let's say you're a 'Senior Analyst' in the company. The term 'analyst' is vague and not define the actual description of your day-to-day job. But it's define your 'structure' and 'responsibilities' in general compared to a 'Junior Analyst'.

> Job Function: the actual function for your position is 'Legal & Dispute Analyst'. With this term, it's now clear that your job is to do something related to 'Legal & Dispute', and you're a senior in that position. It's define your Job Position is 'Senior Analyst at Legal & Dispute' within the corporation.

Now you understand the basic use case of Job Title and Function. Now what is a Job Structure?

> Job Structure: it's the hierarchy (or structure) of Job Title. Job Function have no hierarchy, the hierarchy of Job Function usually defined by the Job Title.

> In example above, we implicitly understand that 'Senior Software Engineer - Project & Development' is a position higher than a 'Mid-Software Engineer' just from their Job Title. It's unnecessary to define structure of Job Position, because the Job Title itself already define the relation.

Okay now, what about the Job Family?

> Job Family: a group of job titles with similar nature. What it means with similar nature? It means I cannot answer that, it's up to the Human Resource People to define the categories, but let's take a concrete example what it should be below.

> Example of Job Family are Marketing, Information & Technology, Human Resource, Executive Management, Legal. As you can see in example before, we take a 'Senior Software Engineer' and 'Senior Analyst', which each of it are within the 'Information & Technology' and another one to 'Legal'.

---

What we want to achieve with this package are:

1. Ability to manage organization-level structure
2. Ability to manage office-level structure